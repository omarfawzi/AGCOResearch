<?php

namespace App\Models\Filters;

use App\Factories\DateFactory;
use App\Repositories\ReportViewRepository;
use App\Repositories\ReportWeightRepository;
use App\Repositories\SavedReportRepository;
use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Constants\ReportType;

class ReportFilter extends ModelFilter
{
    protected $relationsEnabled = true;

    /**
     * @param array $values
     * @return ReportFilter|Builder
     */
    public function company(array $values)
    {
        return $this->whereHas(
            'companies',
            function (Builder $query) use ($values) {
                if (!$this->input('recommended')) {
                    $query->whereIn('Company', $values);
                    /*return $this->whereHas(
                        'companies',
                        function (Builder $query) use ($values) {
                            $query->whereHas(
                                'industry',
                                function (Builder $query) use ($values) {
                                    $query->whereHas(
                                        'companies',
                                        function (Builder $query) use ($values) {
                                            $query->whereIn('Company', $values);
                                        }
                                    );
                                }
                            );
                        }
                    );*/
                }
                if ($this->input('recommendation')) {
                    $query->join(
                        'Recommendation',
                        'Recommendation.RecommendID',
                        '=',
                        'CompanyDetail.RecommendID'
                    )->whereIn('Recommendation.Recommendation', $this->input('recommendation'));
                }
                if ($this->input('sector')) {
                    $query->join(
                        'IndustryDetail',
                        'IndustryDetail.CompanyID',
                        '=',
                        'Company.CompanyID'
                    )->join(
                        'Industry',
                        'Industry.IndustryID',
                        '=',
                        'IndustryDetail.IndustryID'
                    )->join(
                        'GICS_Sector',
                        'GICS_Sector.GICS_SectorId',
                        '=',
                        'Industry.GICS_SectorId'
                    )->whereIn('GICS_Sector.GICS_Sector', $this->input('sector'));
                }
            }
        );
    }

    /**
     * @param array $values
     * @return ReportFilter|Builder
     */
    public function country(array $values)
    {
        return $this->whereHas(
            'countries',
            function (Builder $query) use ($values) {
                $query->whereIn('Country', $values)->orWhereHas(
                    'region',
                    function (Builder $query) use ($values) {
                        $query->whereIn('Region', $values);
                    }
                );
            }
        );
    }

    /**
     * @param string $date
     * @return ReportFilter|\Illuminate\Database\Query\Builder
     */
    public function date(string $date)
    {
        return $this->whereDate('ReportDate', '>=', (new DateFactory())->make($date));
    }

    /**
     * @param array $values
     * @return ReportFilter|Builder
     */
    public function recommendation(array $values)
    {
        if ($this->input('company')) {
            return null;
        } else {
            return $this->whereHas(
                'recommendations',
                function (Builder $query) use ($values) {
                    $query->whereIn('Recommendation', $values);
                }
            );
        }
    }

    /**
     * @param string $searchKey
     * @return ReportFilter|Builder
     */
    public function searchKey(string $searchKey)
    {
        $searchKey = strtolower($searchKey);
        return $this->whereRaw('LOWER(Title) like ?', ["%$searchKey%"])
            ->orWhereRaw(
                'LOWER(FirstLine) like ?',
                ["%$searchKey%"])
            ->orWhereRaw(
                'LOWER(CAST(Synopsis as varchar(1000))) like ?',
                ["%$searchKey%"]
            );
    }

    /**
     * @param array $values
     * @return ReportFilter|Builder|null
     */
    public function sector(array $values)
    {
        if ($this->input('company')) {
            return null;
        }elseif ($this->input('recommended')) {
            return null;
        } else {
            return $this->whereHas(
                'companies',
                function (Builder $query) use ($values) {
                    $query->whereHas(
                        'industry',
                        function (Builder $query) use ($values) {
                            $query->whereHas(
                                'sector',
                                function (Builder $query) use ($values) {
                                    $query->whereIn('GICS_Sector', $values);
                                }
                            );
                        }
                    );
                }
            );
        }
    }

    /**
     * @param string $type
     * @return ReportFilter|Builder
     */
    public function type(string $type)
    {
        $typeID = -1;
        switch($type){
            case 'Company Report':
                $typeID = ReportType::COMPANY;
                break;
            case 'Industry Report':
                $typeID = ReportType::SECTOR;
                break;
            case 'Market Report (General)':
                $typeID = ReportType::MACRO;
                break;
            case 'Daily Report':
                $typeID = ReportType::DAILY;
                break;
            default:
                return;
        }
        return $this->where('TypeID', $typeID);
    }

    /**
     * @param bool $saved
     * @return ReportFilter|null
     */
    public function saved(bool $saved)
    {
        return $saved ? $this->whereIn(
            'ReportID',
            app(SavedReportRepository::class)->getUserSavedReportsIds(Auth::user()->id)
        ) : null;
    }

    /**
     * @param bool $trending
     * @return ReportFilter|null
     */
    public function trending(bool $trending)
    {
        if ($trending) {
            $trendingReportsIds = app(ReportViewRepository::class)->getTrendingReportsIds();

            return $this->whereIn('ReportID', $trendingReportsIds)->orderByRaw(
                order_by_field('ReportID', $trendingReportsIds)
            );
        }

        return null;
    }

    /**
     * @param bool $recommended
     * @return ReportFilter|null
     */
    public function recommended(bool $recommended)
    {
        if ($recommended)
        {
            $searchKeys = array();
            $searchType = '';

            if($this->input('company')){
                $searchKeys = $this->input('company');
                $searchType = 'company';
            }elseif($this->input('sector')){
                $searchKeys = $this->input('sector');
                $searchType = 'sector';
            }

            $recommendedCompanyIds = app(ReportWeightRepository::class)->getWeightedCompanyIds(
                $this->input('rLimit', 10),
                $this->input('rPage', 0),
                $searchKeys,
                $searchType
            );


            return $this->select('Report.*')->join('CompanyDetail', 'CompanyDetail.ReportID', '=', 'Report.ReportID')
                ->whereIn('CompanyDetail.CompanyID', $recommendedCompanyIds)
                ->orderByRaw(
                    order_by_field('CompanyDetail.CompanyID', $recommendedCompanyIds)
                );
        }
        return null;
    }


}
