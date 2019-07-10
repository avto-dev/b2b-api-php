<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Report;

use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUid;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithName;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithTags;
use AvtoDev\B2BApi\Responses\DataTypes\AbstractDataType;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithActive;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithComment;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithCreated;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUpdated;

/**
 * @deprecated This package is abandoned. New package is available here: <https://github.com/avtocod/b2b-api-php>
 */
class ReportData extends AbstractDataType
{
    use WithActive, WithCreated, WithUpdated, WithUid, WithComment, WithTags, WithName;

    /**
     * Стек данных по источникам, запрошенным в отчете. Формируется единожды в конструкторе.
     *
     * @var ReportSource[]
     */
    protected $sources = [];

    /**
     * Счётчик количества завершенных источников.
     *
     * @var int
     */
    protected $sources_count_completed = 0;

    /**
     * Счётчик источников, статус которых "в процессе".
     *
     * @var int
     */
    protected $sources_count_in_progress = 0;

    /**
     * Счётчик количества источников, завершенных с ошибками.
     *
     * @var int
     */
    protected $sources_count_with_errors = 0;

    /**
     * Счётчик количества источников, завершенных успешно.
     *
     * @var int
     */
    protected $sources_count_success = 0;

    /**
     * {@inheritdoc}
     */
    public function __construct($content = null)
    {
        parent::__construct($content);

        // Формируем стек данных об источниках
        if (\is_array($sources = $this->getContentValue('state.sources', null))) {
            foreach ($sources as $source) {
                $this->sources[] = $report_source = new ReportSource($source);

                // Считаем счётчики сразу-же
                if ($report_source->isProgress()) {
                    $this->sources_count_in_progress++;
                } elseif ($report_source->isError()) {
                    $this->sources_count_with_errors++;
                } elseif ($report_source->isSuccess()) {
                    $this->sources_count_success++;
                }

                if ($report_source->isCompleted()) {
                    $this->sources_count_completed++;
                }
            }
        }
    }

    /**
     * Возвращает UID домена.
     *
     * @return null|string
     */
    public function getDomainUid()
    {
        return $this->getContentValue('domain_uid', null);
    }

    /**
     * Возвращает UID типа отчета.
     *
     * @return null|string
     */
    public function getReportTypeUid()
    {
        return $this->getContentValue('report_type_uid', null);
    }

    /**
     * Возвращает ID запрошенного ТС.
     *
     * @return null|string
     */
    public function getVehicleId()
    {
        return $this->getContentValue('vehicle_id', null);
    }

    /**
     * Возвращает тип запрошенного идентификатора.
     *
     * @return null|string
     */
    public function getQueryType()
    {
        return $this->getContentValue('query.type', null);
    }

    /**
     * Возвращает контент запрошенного идентификатора.
     *
     * @return null|string
     */
    public function getQueryBody()
    {
        return $this->getContentValue('query.body', null);
    }

    /**
     * Возвращает число источников, успешно завершившихся.
     *
     * @return int
     */
    public function getSuccessSourcesCount()
    {
        return $this->sources_count_success;
    }

    /**
     * Возвращает true в том случае, если генерация отчета завершена (все источники уже ответили).
     *
     * @return bool
     */
    public function generationIsCompleted()
    {
        return $this->getSourcesCountCompleted() >= $this->getTotalSourcesCount();
    }

    /**
     * Возвращает число источников, обработка которых завершена (источники ответили и нет смысла ждать от них данные).
     *
     * @return int
     */
    public function getSourcesCountCompleted()
    {
        return $this->sources_count_completed;
    }

    /**
     * Возвращает общее количество источников, которые запрошены в данном отчете.
     *
     * @return int
     */
    public function getTotalSourcesCount()
    {
        return count($this->sources);
    }

    /**
     * Возвращает true в том случае, если все источники ответили ошибкой.
     *
     * @return bool
     */
    public function generationIsFailed()
    {
        return $this->getTotalSourcesCount() > 0 && $this->getErrorsSourcesCount() >= $this->getTotalSourcesCount();
    }

    /**
     * Возвращает число источников, обработка которых завершилась с ошибкой.
     *
     * @return int
     */
    public function getErrorsSourcesCount()
    {
        return $this->sources_count_with_errors;
    }

    /**
     * Возвращает true в том случае, если процесс генерации отчета ещё не завершился (в процессе).
     *
     * @return bool
     */
    public function generationIsInProgress()
    {
        return $this->getTotalSourcesCount() > 0 && $this->getProgressSourcesCount() > 0;
    }

    /**
     * Возвращает число источников, находящихся в данный момент в процессе обработки.
     *
     * @return int
     */
    public function getProgressSourcesCount()
    {
        return $this->sources_count_in_progress;
    }

    /**
     * Возвращает объект данных по источнику, извлекая его по имени. В случае его отсутствия вернется null.
     *
     * @param string $source_name
     *
     * @return ReportSource|null
     */
    public function getSourceByName($source_name)
    {
        if ($this->hasSourceName($source_name)) {
            foreach ($this->sources() as $source) {
                if ($source->getName() === $source_name) {
                    return $source;
                }
            }
        }
    }

    /**
     * Возвращает true, если в отчете был запрошен источник с указанным именем (проверяет наличие источника по его
     * имени).
     *
     * @param string $source_name
     *
     * @return bool
     */
    public function hasSourceName($source_name)
    {
        return in_array($source_name, $this->getSourcesNames());
    }

    /**
     * Возвращает массив имен всех источников, что были запрошены в отчете.
     *
     * @return string[]|array
     */
    public function getSourcesNames()
    {
        static $result = [];

        if (empty($result)) {
            foreach ($this->sources() as $source) {
                $result[] = $source->getName();
            }
        }

        return $result;
    }

    /**
     * Возвращает стек данных по источникам (с их статусами).
     *
     * @return ReportSource[]
     */
    public function sources()
    {
        return $this->sources;
    }

    /**
     * Возвращает значения из КОНТЕНТА отчета, обращаясь к нему с помощью dot-нотации.
     *
     * Для более подробной смотри спецификацию по филдам.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return array|mixed|null
     */
    public function getField($path, $default = null)
    {
        if (($current = $this->getContent()) && \is_array($current)) {
            $p = strtok((string) $path, '.');

            while ($p !== false) {
                if (! isset($current[$p])) {
                    return $default;
                }
                $current = $current[$p];
                $p       = strtok('.');
            }
        }

        return $current;
    }

    /**
     * Возвращает контент самого отчета в виде массива.
     *
     * @return array|null
     */
    public function getContent()
    {
        return $this->getContentValue('content', null);
    }
}
