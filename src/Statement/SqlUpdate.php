<?php declare(strict_types=1);

namespace OpenCore\Orm\Statement;

use OpenCore\Orm\Ast\SqlUpdateStatement;
use OpenCore\Orm\Ast\SqlExprField;
use OpenCore\Orm\Ast\SqlExprValue;
use OpenCore\Orm\SqlTable;
use OpenCore\Orm\SqlField;
use OpenCore\Orm\Utils\SqlUtils;
use OpenCore\Orm\Sql;

final class SqlUpdate extends SqlBuilder {

  private readonly SqlUpdateStatement $st;

  public function __construct(SqlTable $table) {
    $this->st = new SqlUpdateStatement($table);
  }

  public function set(SqlField $field, mixed $value): self {
    $this->st->assignments[] = [new SqlExprField($field), new SqlExprValue($value)];
    return $this;
  }

  public function whereEquals(SqlField $field, mixed $value): self {
    $this->st->whereCondition = SqlUtils::andEqualsCondition($this->st->whereCondition, $field, $value);
    return $this;
  }

  public function build(): Sql {
    $ret = new Sql();
    $this->st->buildInto($ret);
    return $ret;
  }

}
