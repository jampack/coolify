<?php

it('uses Gotenberg 9 health-check telemetry flag in the one-click template', function () {
    $compose = file_get_contents(__DIR__.'/../../templates/compose/gotenberg.yaml');

    expect($compose)
        ->toContain('--api-disable-health-check-route-telemetry')
        ->toContain('--api-enable-basic-auth')
        ->not->toContain('--api-disable-health-check-logging');

    foreach (['service-templates.json', 'service-templates-latest.json'] as $templateFile) {
        $templates = json_decode(
            file_get_contents(__DIR__."/../../templates/{$templateFile}"),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );

        expect($templates)->toHaveKey('gotenberg');

        $generatedCompose = base64_decode($templates['gotenberg']['compose'], strict: true);

        expect($generatedCompose)
            ->toContain('--api-disable-health-check-route-telemetry')
            ->toContain('--api-enable-basic-auth')
            ->not->toContain('--api-disable-health-check-logging');
    }
});
