<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * Includes ngrok tunnel domains so the app works correctly
     * when exposed via ngrok port-forwarding.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
            // Allow all ngrok free-tier tunnels (*.ngrok-free.app)
            '^(.+\.)?ngrok-free\.app$',
            // Allow legacy ngrok tunnels (*.ngrok.io)
            '^(.+\.)?ngrok\.io$',
            // Allow ngrok paid tunnels (*.ngrok.app)
            '^(.+\.)?ngrok\.app$',
        ];
    }
}
