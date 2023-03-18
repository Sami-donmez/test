<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.1.6
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2023 KONKORD DIGITAL
 */

namespace App\Enums;

enum DocumentStatus : string {

    case DRAFT    = 'draft';
    case SENT     = 'sent';
    case ACCEPTED = 'accepted';
    case LOST     = 'lost';

    /**
     * Get the status color
     *
     * @return string
     */
    public function color() : string
    {
        return match ($this) {
            DocumentStatus::DRAFT    => '#64748b',
            DocumentStatus::SENT     => '#3b82f6',
            DocumentStatus::ACCEPTED => '#22c55e',
            DocumentStatus::LOST     => '#f43f5e',
        };
    }

    /**
     * Get the status icon
     *
     * @return string
     */
    public function icon() : string
    {
        return match ($this) {
            DocumentStatus::DRAFT    => 'LightBulb',
            DocumentStatus::SENT     => 'Mail',
            DocumentStatus::ACCEPTED => 'Check',
            DocumentStatus::LOST     => 'X',
        };
    }

    /**
     * Get the status displayable name
     *
     * @return string
     */
    public function displayName() : string
    {
        return __('document.status.' . $this->value) ?: $this->value;
    }
}
