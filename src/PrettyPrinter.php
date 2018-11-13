<?php

namespace RoyGoldman\DrupalEnvSettings;

use PhpParser\Node\Expr\Array_;
use PhpParser\PrettyPrinter\Standard;

/**
 * Override Pretty Printer to add formatting to arrays.
 */
class PrettyPrinter extends Standard {

  /**
   * {@inheritdoc}
   *
   * Override the standard printer's pExpr_Array(), in order to inject
   * mutli-line formating to printed array code.
   */
  public function pExpr_Array(Array_ $node) {
    $syntax = $node->getAttribute('kind', $this->options['shortArraySyntax']);
    $result = ($syntax) ? '[' : 'array(';

    // Print items mutliline with trailing comma.
    if (!empty($node->items)) {
      $result .= $this->pCommaSeparatedMultiline($node->items, TRUE);
    }
    return $result . $this->nl . (($syntax) ? ']' : ')');
  }

}
