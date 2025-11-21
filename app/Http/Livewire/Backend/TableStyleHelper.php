<?php


namespace App\Http\Livewire\Backend;


use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TableStyleHelper
{
  public static function setTableStyle(DataTableComponent $table)
  {
    $table->setPerPageVisibilityStatus(false);


    $table->setTableWrapperAttributes([
      'class' => 'table align-middle gs-0'
    ]);
    $table->setTheadAttributes([
      'class' => 'fw-bold bg-none'
    ]);
    $table->setTbodyAttributes([
      'class' => 'px-4',
    ]);
    $table->setThAttributes(function (Column $column) {
      return [
        'class' => 'px-4 py-4'
      ];
    });
    $table->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
      return [
        'default' => true,
        'class' => 'px-4'
      ];
    });

    $table->setTrAttributes(function ($row, $index) {
      return [
        'class' => 'px-4 border-top'
      ];
    });
  }
}