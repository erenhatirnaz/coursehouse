<?php

namespace App;

abstract class PaymentPeriod
{
    public const ONE_TIME = "one_time";
    public const DAILY = "daily";
    public const WEEKLY = "weekly";
    public const MONTHLY = "monthly";
    public const YEARLY = "yearly";
}
