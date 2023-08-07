<?php

namespace App\Models;

use App\Enums\EuCountryEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['bin', 'amount', 'currency', 'bin_country', 'eu', 'amount_eur', 'fee'];

    public function isEu()
    {
        if (!$this->bin_country) {
            $this->getCountryCode();
        }
        $this->eu = EuCountryEnum::check($this->bin_country);

        return $this->eu;
    }

}
