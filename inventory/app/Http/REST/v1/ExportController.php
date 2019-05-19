<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Exports\ViewProductExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Gate;

/**
 * Export resource representation.
 *
 * @Resource("Export", uri="/export")
 */
class ExportController extends ApiBaseController
{
    /**
     * @var Excel
     */
    private $excel;

    /**
     * Initialize @var ViewProduct
     * Initialize @var Excel
     *
     * @Request ViewProduct
     * @Request Excel
     *
     */
    public function __construct(Excel $excel)
    {
        parent::__construct();
        $this->excel = $excel;
        $this->middleware('jwt.verify');
    }

    /**
     * Export to excel.
     *
     * Get an Excel of prodcut by store.
     *
     * @Get("/export/excel/{id}")
     * @Versions({"v1"})
     */
    public function excel($id)
    {
        $YmdHis = date("YmdHis");

        return (new ViewProductExport($id))->download("inventory-report-{$YmdHis}.xlsx");
    }

    /**
     * Export to excel.
     *
     * Get an Excel of prodcut by store.
     *
     * @Get("/export/excel/{id}")
     * @Versions({"v1"})
     */
    public function csv($id)
    {
        $YmdHis = date("YmdHis");

        return (new ViewProductExport($id))->download("inventory-report-{$YmdHis}.csv");
    }

}
