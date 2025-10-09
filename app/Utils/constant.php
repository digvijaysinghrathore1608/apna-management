<?php

const DEFAULT_DATA_LIMIT = 25;
const GENDERS = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
const DURATION_TYPE = ['year' => 'Year', 'month' => 'Month', 'day' => 'Day'];
const ACCOUNT_TYPE = ['savings' => 'Savings', 'current' => 'Current'];
const WORK_TYPE = [
    'salaried' => 'Salaried',
    'business' => 'Business',
    'student' => 'Student',
    'housewife' => 'Housewife',
    'retired' => 'Retired',
    'other' => 'Other',
];
const MINIMUM_AGE = 20;

//loan  status

const LOAN_STATUS_PROCESSING = "processing";
const LOAN_STATUS_PENDING = "pending";
const LOAN_STATUS_APPROVE = "approved";
const LOAN_STATUS_DISBURSED = "disbursed";
const LOAN_STATUS_REJECTED = "rejected";
const LOAN_STATUS_CLOSED = "closed";
