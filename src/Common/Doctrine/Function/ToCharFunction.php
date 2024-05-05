<?php

namespace App\Common\Doctrine\Function;

use Doctrine\ORM\Query\AST\ArithmeticExpression;
use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class ToCharFunction extends FunctionNode {

  protected ?ArithmeticExpression $dateExpression = null;
  protected ?Node $patternExpression = null;

  /**
   * @throws ASTException
   */
  public function getSql(SqlWalker $sqlWalker): string {
    return 'TO_CHAR(' .
        $this->dateExpression?->dispatch($sqlWalker) . ', ' .
        $this->patternExpression?->dispatch($sqlWalker) .
        ')';
  }

  /**
   * @throws QueryException
   */
  public function parse(Parser $parser): void {
    $parser->match(TokenType::T_IDENTIFIER);
    $parser->match(TokenType::T_OPEN_PARENTHESIS);
    $this->dateExpression = $parser->ArithmeticExpression();
    $parser->match(TokenType::T_COMMA);
    $this->patternExpression = $parser->StringPrimary();
    $parser->match(TokenType::T_CLOSE_PARENTHESIS);
  }
}