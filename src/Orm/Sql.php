<?php

declare(strict_types=1);

namespace OpenCore\Orm;

use OpenCore\Orm\Statement\SqlSelect;
use OpenCore\Orm\Statement\SqlDelete;
use OpenCore\Orm\Statement\SqlInsert;
use OpenCore\Orm\Statement\SqlUpdate;
use OpenCore\Orm\Statement\SqlJoin;
use OpenCore\Orm\Statement\SqlCall;
use OpenCore\Orm\Ast\SqlJoinSpec;
use OpenCore\Orm\Ast\SqlExprOpCall;

class Sql {

  public string $sql = '';
  public array $params = [];
  private $tableAliases = [];
  public bool $tableAliasesEnabled = false;

  public static function select(): SqlSelect {
    return new SqlSelect();
  }

  public static function delete(SqlTable $table): SqlDelete {
    return new SqlDelete($table);
  }

  public static function insert(SqlTable $table): SqlInsert {
    return new SqlInsert($table);
  }

  public static function update(SqlTable $table): SqlUpdate {
    return new SqlUpdate($table);
  }

  public static function innerJoin(SqlTable $table): SqlJoin {
    return new SqlJoin(SqlJoinSpec::JOIN_INNER, $table);
  }

  public static function count(SqlField $field): SqlCall {
    return (new SqlCall(SqlExprOpCall::FN_COUNT))->withFieldArg($field);
  }

  public static function max(SqlField $field): SqlCall {
    return (new SqlCall(SqlExprOpCall::FN_MAX))->withFieldArg($field);
  }

  public static function min(SqlField $field): SqlCall {
    return (new SqlCall(SqlExprOpCall::FN_MIN))->withFieldArg($field);
  }

  public static function avg(SqlField $field): SqlCall {
    return (new SqlCall(SqlExprOpCall::FN_AVG))->withFieldArg($field);
  }

  public static function naturalJoin(SqlTable $table): SqlJoin {
    return new SqlJoin(SqlJoinSpec::JOIN_NATURAL, $table);
  }

  public function getTableAlias(SqlTable $table) {
    $id = $table->id;
    if (!isset ($this->tableAliases[$id])) {
      $this->tableAliases[$id] = $table->name[0] . (count($this->tableAliases) + 1);
    }
    return $this->tableAliases[$id];
  }

}
