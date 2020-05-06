<?php

namespace App;

abstract class ApplicationStatus
{
    public const NEW = "new";
    public const APPROVED = "approved";
    public const REJECTED = "rejected";
    public const CANCELLED = "cancelled";
}
