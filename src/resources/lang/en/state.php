<?php

return [
    'mysql'   => [
        // MySQL Error: #1217, #1451
        "CANNOT_DELETE_FOREIGN_KEY_CONSTRAINT_FAILS" => "Cannot delete or update a child row: a foreign key constraint fails",
        // MySQL Error: #1048
        "INTEGRITY_CONSTRAINT_VIOLATION"             => "Integrity constraint violation, ':msg'",
        // MySQL Error: #1406
        "DATA_TOO_LONG_FOR_COLUMN"                   => "Data too long for column ':field'",
        // MySQL Error: #1216, #1452
        "CANNOT_ADD_FOREIGN_KEY_CONSTRAINT_FAILS"    => "Cannot add or update a child row: a foreign key constraint fails",
        // MySQL Error: #1062, #1586
        "DUPLICATE_ENTRY"                            => "The request could not be completed due to a conflict, duplicate entry ':value' for key ':field'",
    ],
    'generic' => [
        // Bad Request: General error
        "ERROR_MESSAGE_CODE_NUMBER_400" => "The request could not be understood by the server due to malformed syntax",
        // Bad Request: Not found
        "ERROR_MESSAGE_CODE_NUMBER_404" => "The requested resource could not be found but may be available again in the future",
        // Bad Request: Conflict
        "ERROR_MESSAGE_CODE_NUMBER_409" => "The request could not be completed due to a conflict",
        // Server Error: A generic error message
        "ERROR_MESSAGE_CODE_NUMBER_500" => "The server encountered an unexpected condition which prevented it from fulfilling the request",
        // Server Error: Service Unavailable
        "ERROR_MESSAGE_CODE_NUMBER_503" => "The server is currently unable to handle the request due to a temporary overloading or maintenance of the server",
    ],
];
