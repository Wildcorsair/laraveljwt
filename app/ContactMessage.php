<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{

  /**
   * Accessor for 'seed_investor' field.
   */
  public function getSeedInvestorAttribute($value) {
    return ($value) ? 'Seed Investor' : null;
  }

  /**
   * Accessor for 'service_provider' field.
   */
  public function getServiceProviderAttribute($value) {
    return ($value) ? 'Service Provider' : null;
  }

  /**
   * Accessor for 'reatil_investor' field.
   */
  public function getRetailInvestorAttribute($value) {
    return ($value) ? 'Retail Investor' : null;
  }

  /**
   * Accessor for 'institutional' field.
   */
  public function getInstitutionalAttribute($value) {
    return ($value) ? 'Institutional' : null;
  }

  /**
   * Accessor for 'government' field.
   */
  public function getGovernmentAttribute($value) {
    return ($value) ? 'Government/Regulator' : null;
  }

  /**
   * Accessor for 'media' field.
   */
  public function getMediaAttribute($value) {
    return ($value) ? 'Press/Media' : null;
  }

}
