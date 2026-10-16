# Research Output Workflow — Implementation Plan

## Background

Currently, the four research output types (`research`, `conference_papers`, `preprints`, `books`) have **no formal approval state**. When a researcher submits an output, it lands directly in the repository and the only control Admin has is a manual `is_public` visibility toggle. There is:

- No `status` column (`pending`, `approved`, `rejected`, `revision_required`)
- No "review queue" for Admin
- No automatic `is_public` enforcement tied to approval state
- No feedback/remarks channel back to the researcher
- No notification when status changes

This plan introduces a `Pending → Admin Review → Approved / Rejected / Revision Required` workflow across all four output types, mirroring the existing `ProposalSubmission` pattern already in the codebase.

---

## Proposed Status Lifecycle

```
[Researcher submits]
        ↓
    pending  ←──────────────────────────────────────────┐
        ↓                                               │
[Admin reviews in queue]                                │
        ├──► approved   → is_public auto-forced true    │
        ├──► rejected   → is_public auto-forced false   │
        └──► revision   → researcher edits & resubmits ─┘
```

| Status | Who sets it | `is_public` behavior |
|---|---|---|
| `pending` | System on submit | Remains `false` (hidden from public) |
| `approved` | Admin | Automatically set to `true` |
| `rejected` | Admin | Automatically set to `false` |
| `revision_required` | Admin | Remains `false`; researcher can resubmit |

> **⚠️ BREAKING CHANGE:** On submit, `is_public` will be **forced to `false`** regardless of what the user selects. Admin approval is the only path to public visibility. This differs from the current behavior where researchers could self-publish.

> **⚠️ DATA MIGRATION NOTE:** Existing records with `is_public = true` will be back-filled with `status = 'approved'`. Records with `is_public = false` will be back-filled as `status = 'pending'`. Review this assumption before running.

---

## Open Questions

**Q1 — Visibility override:** Should Admin still be able to manually toggle `is_public` independently of the approval status, or should `is_public` be entirely driven by `status`?
*(Recommendation: `is_public` is auto-driven by `status`, and the old toggle is removed/locked.)*

**Q2 — Revision resubmission:** When a researcher resubmits after `revision_required`, should the status automatically return to `pending` on `update()`, or should it remain `revision_required` until they explicitly click "Resubmit for Review"?
*(Recommendation: a dedicated "Resubmit" action that resets status to `pending`.)*

**Q3 — Notification scope:** Should in-app notifications be included in this phase, or deferred to the separate In-App Notification feature (section 7.10 of the documentation)?
*(Recommendation: defer notifications to a follow-up task, focusing only on the status mechanics and UI.)*

---

## Proposed Changes

### Phase 1 — Database: Add `status` & `admin_remarks` to all four output tables

#### [NEW] `add_status_to_research_outputs_table.php` (migration)
A single migration that alters all four tables: `research`, `conference_papers`, `preprints`, `books`.

Each table receives:
- `status` — `ENUM('pending', 'approved', 'rejected', 'revision_required')` — default `'pending'`
- `admin_remarks` — `TEXT` nullable — stores reviewer feedback

The migration will also back-fill:
- `status = 'approved'` where `is_public = 1`
- `status = 'pending'` where `is_public = 0`

---

### Phase 2 — Models: Add `status` to `$fillable` and `$casts`

#### [MODIFY] `app/Models/Research.php`
- Add `'status'` and `'admin_remarks'` to `$fillable`
- Add helper scopes: `scopePending()`, `scopeApproved()`, `scopeRejected()`

#### [MODIFY] `app/Models/ConferencePaper.php`
- Same additions as `Research.php`
- Add missing `user()` relationship

#### [MODIFY] `app/Models/Preprint.php`
- Same additions as `Research.php`

#### [MODIFY] `app/Models/Book.php`
- Same additions as `Research.php`

---

### Phase 3 — User-Side Controllers: Force `pending` on submit; reset on resubmit

#### [MODIFY] `app/Http/Controllers/Users/UserResearchCTRL.php`
- `store()`: Force `status = 'pending'` and `is_public = false` on creation (ignore user's `is_public` input)
- `update()`: Do NOT change `status` unless the current status is `revision_required`
- Add `resubmit(Request $request, Research $research)` method: validates ownership, sets `status = 'pending'`, `is_public = false`
- Remove or lock `updateVisibility()` so it only works when `status = 'approved'`

#### [MODIFY] `app/Http/Controllers/Users/UserPreprintController.php`
- Same changes as `UserResearchCTRL`

#### [MODIFY] `app/Http/Controllers/Users/UserConferencePaperController.php`
- Same changes as `UserResearchCTRL`

#### [MODIFY] `app/Http/Controllers/Users/UserBookController.php`
- Same changes as `UserResearchCTRL`

---

### Phase 4 — Admin-Side Controllers: Add `updateStatus()` method

#### [MODIFY] `app/Http/Controllers/Admin/ResearchController.php`
- `index()`: Add a `pending` filter tab (default view shows pending items first)
- Add `updateStatus(Request $request, Research $research)` method:
  - Validates: `status` in `['approved', 'rejected', 'revision_required']`, `admin_remarks` nullable string
  - When `approved`: sets `status = 'approved'` AND `is_public = true`
  - When `rejected`: sets `status = 'rejected'` AND `is_public = false`
  - When `revision_required`: sets `status = 'revision_required'` AND `is_public = false`
  - Logs the action to the `logs` table via the existing `CTRLlogs` pattern

#### [MODIFY] `app/Http/Controllers/Admin/ConferencePaperController.php`
- Same `updateStatus()` method

#### [MODIFY] `app/Http/Controllers/Admin/PreprintController.php`
- Same `updateStatus()` method

#### [MODIFY] `app/Http/Controllers/Admin/BookController.php`
- Same `updateStatus()` method

---

### Phase 5 — Routes: New status + resubmit endpoints

#### [MODIFY] `routes/web.php`

**Admin routes** (inside `prefix('admin')` group):
```php
Route::post('research/{research}/status', [ResearchController::class, 'updateStatus'])->name('research.status');
Route::post('conference-papers/{conference_paper}/status', [ConferencePaperController::class, 'updateStatus'])->name('conference-papers.status');
Route::post('preprints/{preprint}/status', [PreprintController::class, 'updateStatus'])->name('preprints.status');
Route::post('books/{book}/status', [BookController::class, 'updateStatus'])->name('books.status');
```

**User routes** (inside `prefix('users')` group):
```php
Route::post('/research/{research}/resubmit', [UserResearchCTRL::class, 'resubmit'])->name('research.resubmit');
Route::post('/preprints/{preprint}/resubmit', [UserPreprintController::class, 'resubmit'])->name('preprints.resubmit');
Route::post('/conference-papers/{conference_paper}/resubmit', [UserConferencePaperController::class, 'resubmit'])->name('conference-papers.resubmit');
Route::post('/books/{book}/resubmit', [UserBookController::class, 'resubmit'])->name('books.resubmit');
```

---

### Phase 6 — Admin Views: Review Queue + Status Action Panel

#### [MODIFY] `resources/views/admin/research/index.blade.php`
- Add a **"Pending Review"** tab/filter badge with pending count
- Add status badge column (`pending` = yellow, `approved` = green, `rejected` = red, `revision_required` = orange)

#### [MODIFY] `resources/views/admin/research/partials/research/show.blade.php`
- Add an **"Approval Decision"** card panel:
  - Dropdown: Approve / Reject / Request Revision
  - `admin_remarks` textarea
  - Submit button (POST to `admin.research.status`)

#### [MODIFY] Same show views for `conference`, `preprint`, `book` partials
- Apply the same "Approval Decision" panel pattern

---

### Phase 7 — User Views: Status Visibility + Resubmit Action

#### [MODIFY] `resources/views/users/research/index.blade.php`
- Show status badge per output (`pending`, `approved`, `rejected`, `revision_required`)
- Show `admin_remarks` as an inline info card when status is `revision_required` or `rejected`
- Show a "Resubmit for Review" button when `status = 'revision_required'`

#### [MODIFY] Same user index views for preprints, conference papers, books

---

### Phase 8 — Dashboard Data Feeds: Include `status` in JSON

#### [MODIFY] `routes/web.php` — inline route closures
- Update `/research/list`, `/preprints/list`, `/conference-papers/list`, `/books/list` to include `status` and `admin_remarks` fields in the JSON response (used by the JS dashboard widget)

---

## Verification Plan

### Manual Verification

1. **Submission gating** — As a Researcher, submit a new research paper → confirm `status = 'pending'` in DB and `is_public = false`.
2. **Admin queue** — Log in as Admin → navigate to research index → confirm "Pending Review" filter shows the submission.
3. **Approve flow** — Admin approves → confirm `status = 'approved'` in DB, `is_public = true`, and output appears on public portal.
4. **Reject flow** — Admin rejects with a remark → confirm `status = 'rejected'`, `is_public = false`. Researcher sees remark on their dashboard.
5. **Revision flow** — Admin sets `revision_required` → Researcher sees remark + "Resubmit" button → Researcher resubmits → `status` resets to `pending`.
6. **Backward compat** — Confirm existing approved records (back-filled by migration) are still publicly visible.
7. **All 4 types** — Repeat steps 1–5 for Conference Papers, Preprints, and Books.

---

*CVHRDC — Research Output Workflow Implementation Plan*
*Created: July 2026*
