<?php

namespace App\Constants;

class Constant
{
    const CLIENT_ACCOUNT_TYPE = [
        "0"  => "Undefined",
        "1"  => "Cash - CDN",
        "2"  => "Cash - US",
        "3"  => "Margin - CDN",
        "4"  => "Margin – US",
        "5"  => "RRSP",
        "6"  => "Spousal RRSP",
        "7"  => "TFSA",
        "8"  => "RRIF",
        "9"  => "Spousal RRIF",
        "10" => "LIRA",
        "11" => "LIF",
        "12" => "RESP",
        "13" => "RDSP",
        "14" => "Hedge – CDN",
        "15" => "Hedge - US",
        "16" => "Option - CDN",
        "17" => "Option – US",
        "18" => "Short – CDN",
        "19" => "Short – US",
        "20" => "LIRA – US",
        "21" => "LRIF",
        "22" => "LRSP",
        "23" => "LRSP - US",
        "24" => "PRIF",
        "25" => "RLIF",
        "26" => "RLSP",
        "27" => "RLSP - US",
        "28" => "RRSP - US",
        "29" => "Spousal RRSP - US",
        "30" => "SSP II",
        "31" => "TFSA - US",
        "32" => "Cash",
        "33" => "NON-REG",
        "34" => "RRSP & OTHER"
    ];

    const OWNERSHIP_ARRAY = [
        1 => "Individual",
        2 => "Joint",
        3 => "Corp",
    ];

    const RISK_RANK_ARRAY = [
        1 => "Low",
        2 => "Low-Medium",
        3 => "Medium",
        4 => "Medium-High",
        5 => "High"
    ];

    const MARKET_TYPE_ARRAY = [
        1 => "Canadian Stock",
        2 => "US Stock",
        3 => "Mutual Fund",
        4 => "Bonds & Debentures"
    ];


    const COLOR_ARRAY_PDF = [
        "#132575", "#FFBB0F", "#B80241", "#262626", "#3C3C3C", "#E3E2E2", "#D1D0D0", "#F4F4F4", "#a5a7a6", "#aaaaaa", "#c0c0c0", "#e6e6e6", "#132575", "#FFBB0F", "#B80241", "#262626", "#3C3C3C", "#E3E2E2", "#D1D0D0", "#F4F4F4", "#a5a7a6", "#aaaaaa", "#c0c0c0", "#e6e6e6"
    ];
    const DATA_FOLDER =  'data/';
    const REMITTANCE_FOLDER =  'remittance_sheet/';
    const STORAGE_FOLDER = 'storage/';
    const FUND_TRANSFER_FOLDER = 'fund-transfer-agreements/';
    const QUESTIONNAIRE_FOLDER = 'questions/';
    const VOID_CHEQUE = 'void_cheque/';
    const PHOTO_ID = 'photo_id/';
    const FORMS_FOLDER = 'forms/';
    const AGREEMENT_FOLDER = 'agreements/';
    const RISK_ASSESSMENT_FOLDER = 'risk_assessment/';
    const ANNUAL_REPORT_FOLDER = 'annual_report/';
    const RISK_ASSESSMENT_FILE = 'risk_assessment_';
    const RECOMMENDED_PORTFOLIO_COMPOSITION = 'recommended_portfolio_composition.pdf';
    const MAX_LOGIN_ATTEMPT = 10;
}
