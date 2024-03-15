<?php declare(strict_types=1);

namespace OpenCore\Orm;

final class TableDefinitionBuilder {

  private array $fields;
  private array $props;
  private ?array $foreign = null;
  private ?string $primary = null;

  public function __construct(
    private readonly string $table,
    private readonly ?string $parentClass = null,
  ) {
  }

  public function withField(string $name, string $property = null, bool $primary = false, string $foreign = null) {
    if ($property === null) {
      $property = $name;
    }
    $this->props[] = $property;
    $this->fields[$property] = $name;
    if ($foreign !== null) {
      $this->foreign[$property] = $foreign;
    }
    if ($primary !== false) {
      $this->primary = $property;
    }
    return $this;
  }

  public function build() {
    return new TableDefinition($this->table, $this->props, $this->fields, $this->foreign,
      $this->primary, parentClass: $this->parentClass);
  }

}
