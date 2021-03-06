<?php

namespace App\Transformers;

use App\Models\SQL\Report;
use League\Fractal\TransformerAbstract;

class ReportTransformer extends TransformerAbstract
{
    /**
     * @param Report $report
     * @return array
     */
    public function transform(Report $report): array
    {
        return [
            'id' => $report->ReportID,
            'title' => $report->report_title,
            'synopsis' => $report->Synopsis,
            'summary' => $report->ReportSummary,
            'date' => $report->ReportDate,
            'pages' => $report->Pages,
            'by' => $report->AnalystIndex,
            'type' => optional($report->type)->Type
        ];
    }
}
