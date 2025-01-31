<?php

return [
    'decimal_precision' => env('CURRENCY_DECIMAL_PRECISION', 3),
    'decimal_separator' => env('CURRENCY_DECIMAL_SEPARATOR', '.'),

    // ISO 4217 valid currency codes
    'valid_iso_codes' => [
        "AED","AFN","ALL","AMD","ANG","ANG","AOA","ARS","AUD","AWG","AZN",
        "BAM","BBD","BDT","BGN","BHD","BIF","BMD","BND","BOB","BOV","BRL","BSD","BTN","BWP","BYN","BZD",
        "CAD","CDF","CHE","CHF","CHW","CLF","CLP","CNY","COP","COU","CRC","CUC","CUP","CVE","CZK",
        "DJF","DKK","DOP","DZD",
        "EGP","ERN","ETB","EUR","FJD","FKP","GBP","GEL","GHS","GIP","GMD","GNF","GTQ","GYD",
        "HKD","HNL","HRK","HTG","HUF","IDR","ILS","INR","INR","IQD","IRR","ISK","JMD","JOD","JPY",
        "KES","KGS","KHR","KMF","KPW","KRW","KWD","KYD","KZT","LAK","LBP","LKR","LRD","LSL","LYD",
        "MAD","MAD","MDL","MGA","MKD","MMK","MNT","MOP","MRU","MUR","MVR","MWK","MXN","MXV","MYR","MZN",
        "NAD","NGN","NIO","NOK","NOK","NOK","NPR","NZD","NZD","NZD","NZD","NZD","OMR",
        "PAB","PEN","PGK","PHP","PKR","PLN","PYG","QAR","RON","RSD","RUB","RWF",
        "SAR","SBD","SCR","SDG","SEK","SGD","SHP","SLL","SOS","SRD","SSP","STN","SVC","SYP","SZL",
        "THB","TJS","TMT","TND","TOP","TRY","TTD","TWD","TZS",
        "UAH","UGX","USD","USN","UYI","UYU","UZS","VED","VEF","VND","VUV","WST",
        "XAF","XCD","XDR","XOF","XPF","XSU","XUA","YER","ZAR","ZAR","ZMW","ZWL",
    ],
];
