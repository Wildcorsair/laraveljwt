<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * Relation to countries table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country() {
        return $this->belongsTo('App\Country');
    }

    /**
     * Relation to sectors table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector() {
        return $this->belongsTo('App\Sector');
    }

    /**
     * Relation to trading_blocks table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trading_block() {
        return $this->belongsTo('App\TradingBlock');
    }

    public function type() {
        return $this->belongsTo('App\Type');
    }
}
