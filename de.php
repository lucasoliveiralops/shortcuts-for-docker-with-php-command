#!/usr/bin/env php
<?php

$personalFolder = personalFolder();
$archiveSettings = ".settings-from-op-shell";
$currentDiretory = getcwd();
$queryToExec = $argv;
unset($queryToExec[0]);
$queryToExec = implode(" ", $queryToExec);

if (! file_exists($personalFolder . $archiveSettings)) {
    echo "Settings archive not found in: " . $personalFolder . $archiveSettings;
}

$settings = json_decode(
    file_get_contents($personalFolder . $archiveSettings),
    true
);

if (empty($settings) || ! is_array($settings)) {
    echo "Settings is invalid JSON or is empty";
}

foreach ($settings as $setting) {
    if ($currentDiretory !== $personalFolder . $setting["folder"]) {
        continue;
    }
    $queryToExec = transformSuggarCommand($queryToExec);
    $exec = execCommand($queryToExec, $setting);
}

if (! $exec) {
    echo "\033[31mNão foi encontrado nenhum container docker configurado para \033[0m"
        . $currentDiretory
        . PHP_EOL . "\033[36mArquivo de configuração: \033[0m"
        . $personalFolder . $archiveSettings;
}


function execCommand($command, $setting)
{
    $command = ! empty($setting['runCommand']) ? $setting['runCommand'] . '&& ' . $command : $command;
    $exec = shell_exec(getCommandMounted($command, $setting['dockerName']));

    fwrite(
        STDERR,
        "\033[32mO comando abaixo foi executado no seguinte container:  \033[0m" .
        $setting["dockerName"]
    );
    echo PHP_EOL . PHP_EOL . $exec;

    return ! empty($exec);
}


function transformSuggarCommand($command)
{
    $archiveContentsSuggarSyntax = '.suggar-syntax-from-op-shell';
    if (! file_exists(personalFolder() . $archiveContentsSuggarSyntax)) {
        return $command;
    }
    $suggarSyntex = json_decode(file_get_contents(personalFolder() . $archiveContentsSuggarSyntax));
    if (empty($suggarSyntex) || ! is_object($suggarSyntex)) {
        return $command;
    }
    $command = trim($command);
    $partialCommandExploded = explode(' ', $command);
    $partialCommand = '';
    foreach ($partialCommandExploded as $key => $value) {
        $partialCommand .= ' ' . $value;
        $partialCommand = trim($partialCommand);
        if (! empty($suggarSyntex->$partialCommand)) {
            echo PHP_EOL . "\033[36mEste comando é um aliases de: \033[0m" . $suggarSyntex->$partialCommand . PHP_EOL . PHP_EOL;

            return $suggarSyntex->$partialCommand . ' ' . implode(' ', array_slice($partialCommandExploded, $key + 1));
        }
    }

    return $command;
}

function personalFolder()
{
    return DIRECTORY_SEPARATOR . "home" . DIRECTORY_SEPARATOR . get_current_user() . DIRECTORY_SEPARATOR;
}


function getCommandMounted($command, $dockerName)
{
    $defaultCommands = [
        'restart' => "docker restart $dockerName"
    ];
    if (empty($defaultCommands[$command])) {
        return "docker exec -it " . $dockerName . " sh -c '" . $command . "'";
    }

    return $defaultCommands[$command];
}