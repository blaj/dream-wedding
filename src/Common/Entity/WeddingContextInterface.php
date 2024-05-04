<?php

namespace App\Common\Entity;

use App\Wedding\Entity\Wedding;

interface WeddingContextInterface {

  function getWedding(): Wedding;

  function setWedding(Wedding $wedding): self;
}