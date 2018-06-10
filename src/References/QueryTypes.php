<?php

namespace AvtoDev\B2BApi\References;

/**
 * Справочник: Типы идентификаторов.
 */
class QueryTypes extends AbstractReference
{
    /**
     * Тип обозначает необходимость в автоматическом определении типа.
     */
    const QUERY_TYPE_AUTO = 'AUTODETECT';

    /**
     * VIN-код транспортного средства (ВИН).
     */
    const QUERY_TYPE_VIN = 'VIN';

    /**
     * Государственный регистрационный знак (ГРЗ).
     */
    const QUERY_TYPE_GRZ = 'GRZ';

    /**
     * Номер свидетельства о регистрации транспортного средства (СТС).
     */
    const QUERY_TYPE_STS = 'STS';

    /**
     * Номер паспорта транспортного средства (ПТС).
     */
    const QUERY_TYPE_PTS = 'PTS';

    /**
     * Номер шасси транспортного средства.
     */
    const QUERY_TYPE_CHASSIS = 'CHASSIS';

    /**
     * Номер кузова транспортного средства.
     */
    const QUERY_TYPE_BODY = 'BODY';

    /**
     * Номер водительского удостоверения (driver license number).
     */
    const QUERY_TYPE_DRIVER_LICENSE_NUMBER = 'DLN';

    /**
     * {@inheritdoc}
     */
    public static function getAll()
    {
        return [
            static::QUERY_TYPE_AUTO,
            static::QUERY_TYPE_VIN,
            static::QUERY_TYPE_GRZ,
            static::QUERY_TYPE_STS,
            static::QUERY_TYPE_PTS,
            static::QUERY_TYPE_CHASSIS,
            static::QUERY_TYPE_BODY,
            static::QUERY_TYPE_DRIVER_LICENSE_NUMBER,
        ];
    }
}
