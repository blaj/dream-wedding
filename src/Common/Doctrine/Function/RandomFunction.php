<?php

namespace App\Common\Doctrine\Function;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class RandomFunction extends FunctionNode {

  private Node|string|null $expression = null;

  public function getSql(SqlWalker $sqlWalker): string {
    if ($this->expression !== null) {
      $expression =
          $this->expression instanceof Node
              ? $this->expression->dispatch($sqlWalker)
              : $this->expression;

      return 'RANDOM(' . $expression . ')';
    }

    return 'RANDOM()';
  }

  public function parse(Parser $parser): void {
    $lexer = $parser->getLexer();
    $parser->match(TokenType::T_IDENTIFIER);
    $parser->match(TokenType::T_OPEN_PARENTHESIS);

    if ($lexer->lookahead?->type !== TokenType::T_CLOSE_PARENTHESIS) {
      $this->expression = $parser->SimpleArithmeticExpression();
    }

    $parser->match(TokenType::T_CLOSE_PARENTHESIS);
  }
}