<?php

namespace App\Services;

class PaymentInstructionService
{
    public const TYPES = [
        'personal' => 'Personal (পার্সোনাল)',
        'merchant' => 'Merchant (মার্চেন্ট)',
        'agent'    => 'Agent (এজেন্ট)',
    ];

    public static function normalizeType(?string $type): string
    {
        $type = strtolower(trim($type ?? 'personal'));

        return array_key_exists($type, self::TYPES) ? $type : 'personal';
    }

    public static function typeLabel(string $type): string
    {
        return self::TYPES[self::normalizeType($type)] ?? self::TYPES['personal'];
    }

    /**
     * Checkout UI — action label, account type, numbered steps.
     */
    public static function forMethod(string $provider, string $accountType, string $number, float $amount): array
    {
        $provider    = strtolower($provider);
        $accountType = self::normalizeType($accountType);
        $amountFmt   = number_format($amount, 2);

        $config = self::providerConfig($provider, $accountType);

        $steps = array_map(function (string $step) use ($number, $amountFmt) {
            return str_replace(
                ['{number}', '{amount}'],
                [$number, $amountFmt],
                $step
            );
        }, $config['steps']);

        return [
            'provider'           => $provider,
            'account_type'       => $accountType,
            'account_type_label' => self::typeLabel($accountType),
            'action_label'       => $config['action_label'],
            'action_label_bn'    => $config['action_label_bn'],
            'number'             => $number,
            'amount'             => $amountFmt,
            'steps'              => $steps,
        ];
    }

    protected static function providerConfig(string $provider, string $accountType): array
    {
        $map = [
            'bkash' => [
                'personal' => [
                    'action_label'    => 'Send Money',
                    'action_label_bn' => 'সেন্ড মানি',
                    'steps'           => [
                        'bKash App খুলুন → <b>Send Money</b> সিলেক্ট করুন',
                        'Personal (পার্সোনাল) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার bKash নম্বর লিখুন',
                    ],
                ],
                'merchant' => [
                    'action_label'    => 'Payment',
                    'action_label_bn' => 'পেমেন্ট',
                    'steps'           => [
                        'bKash App খুলুন → <b>Payment</b> → <b>Merchant Pay</b> সিলেক্ট করুন',
                        'Merchant (মার্চেন্ট) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার bKash নম্বর লিখুন',
                    ],
                ],
                'agent' => [
                    'action_label'    => 'Cash In',
                    'action_label_bn' => 'ক্যাশ ইন',
                    'steps'           => [
                        'bKash App খুলুন → <b>Cash In</b> সিলেক্ট করুন',
                        'Agent (এজেন্ট) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার bKash নম্বর লিখুন',
                    ],
                ],
            ],
            'nagad' => [
                'personal' => [
                    'action_label'    => 'Send Money',
                    'action_label_bn' => 'সেন্ড মানি',
                    'steps'           => [
                        'Nagad App খুলুন → <b>Send Money</b> সিলেক্ট করুন',
                        'Personal (পার্সোনাল) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার Nagad নম্বর লিখুন',
                    ],
                ],
                'merchant' => [
                    'action_label'    => 'Merchant Pay',
                    'action_label_bn' => 'মার্চেন্ট পে',
                    'steps'           => [
                        'Nagad App খুলুন → <b>Merchant Pay</b> সিলেক্ট করুন',
                        'Merchant (মার্চেন্ট) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার Nagad নম্বর লিখুন',
                    ],
                ],
                'agent' => [
                    'action_label'    => 'Cash In',
                    'action_label_bn' => 'ক্যাশ ইন',
                    'steps'           => [
                        'Nagad App খুলুন → <b>Cash In</b> সিলেক্ট করুন',
                        'Agent (এজেন্ট) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার Nagad নম্বর লিখুন',
                    ],
                ],
            ],
            'rocket' => [
                'personal' => [
                    'action_label'    => 'Send Money',
                    'action_label_bn' => 'সেন্ড মানি',
                    'steps'           => [
                        'Rocket App / USSD → <b>Send Money</b> সিলেক্ট করুন',
                        'Personal (পার্সোনাল) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার Rocket নম্বর লিখুন',
                    ],
                ],
                'merchant' => [
                    'action_label'    => 'Merchant Payment',
                    'action_label_bn' => 'মার্চেন্ট পেমেন্ট',
                    'steps'           => [
                        'Rocket App → <b>Merchant Payment</b> সিলেক্ট করুন',
                        'Merchant (মার্চেন্ট) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার Rocket নম্বর লিখুন',
                    ],
                ],
                'agent' => [
                    'action_label'    => 'Cash In',
                    'action_label_bn' => 'ক্যাশ ইন',
                    'steps'           => [
                        'Rocket App / Agent → <b>Cash In</b> সিলেক্ট করুন',
                        'Agent (এজেন্ট) নম্বর দিন: <b>{number}</b>',
                        'Amount লিখুন: <b>{amount} BDT</b> (ঠিক এই amount)',
                        'Confirm করে PIN দিন',
                        'TrxID কপি করে নিচের ফর্মে দিন + আপনার Rocket নম্বর লিখুন',
                    ],
                ],
            ],
        ];

        return $map[$provider][$accountType] ?? $map['bkash']['personal'];
    }
}
