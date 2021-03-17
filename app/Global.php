<?php

// use DateTime;
use App\Models\Agent;
use App\Services\PushNotif;

function contents_path($append = "")
{
    return "public/contents/" . $append;
}

function agent()
{
    return new Agent();
}

function agent_pending()
{
    $agent = agent()->where("status_id", 1)->count();

    return $agent;
}

function agent_active()
{
    $agent = agent()->whereIn("status_id", [2, 4])->count();

    return $agent;
}

function push_notif()
{
    $push = new PushNotif();

    return $push;
}

function uang($int)
{
    return str_replace(",", ".", number_format($int));
}

function setting_static()
{
    return new \App\Models\SettingStatic();
}

function nameOfMonth($monthNum)
{
    $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
    return $monthName = $dateObj->format('F'); // March
}
