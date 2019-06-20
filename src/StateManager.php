<?php

namespace Putchi\StateKeepers;

use Putchi\StateKeepers\Exceptions\CustomException;
use Putchi\StateKeepers\Exceptions\ProjectException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;
use Exception;
use ErrorException;
use Closure;

class StateManager {

    /**
     * @param Closure      $callable
     * @param Closure|null $errorCatchCallable - (optional) pass a second argument if you want to catch the exception yourself
     * @param string       $errorType
     * @return mixed|bool
     * @throws ProjectException
     * @example
     *      return StateManager::guard(function () {
     *          // Call some function in some model...
     *          // #  e.g: return Model::someFunction($arg1, $arg2);
     *          // Side note: don't forget to use whatever parameters you want in the declaration of the anonymous function (above)...
     *          // #  e.g: function () use ($arg1, $arg2) {...}
     *      }, function (\Throwable $exception) {
     *          // HERE WE CATCH THE ERROR (IF THERE WAS ONE)
     *          // you can use the $exception to show the error massage like: $exception->getMessage();
     *          // Or you can implement your own customized catch callback in here.
     *      });
     * @notice
     *      In case you don't use the second argument for a catch callback, it is strongly suggested to use an external try
     *      and catch block.
     */
    public static function guard(Closure $callable, Closure $errorCatchCallable = null, string $errorType = 'error') {
        $returnValue = [];
        $keepers     = config('state.stateKeepers');
        $errType     = $errorType; // default error type = 'error'
        $msg         = null;

        try {
            foreach ($keepers as $keeper) {
                $result = $keeper::keep($callable);

                if (!isset($returnValue['final'])) {
                    $returnValue['final'] = $result;
                }
            }

            return $returnValue['final'];
        } catch (CustomException $ce) { // Catches also all instances of ProjectException
            $msg     = self::constructMessage($ce);
            $errType = $ce->getType();

            if (is_callable($errorCatchCallable)) {
                return $errorCatchCallable($ce);
            } else {
                throw new ProjectException($ce->getMessage(), $errType, $ce->getCode(), null, $ce);
            }
        } catch (ModelNotFoundException $mnfe) {
            $errCode       = 404;
            $errMsg        = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_404');
            $mnfeException = new ProjectException($mnfe->getMessage(), $errType, $errCode, [
                'model' => $mnfe->getModel(),
                'ids'   => $mnfe->getIds()
            ], $mnfe);
            $msg           = self::constructMessage($mnfeException, $errMsg, $errCode);

            if (is_callable($errorCatchCallable)) {
                return $errorCatchCallable($mnfeException);
            } else {
                throw $mnfeException;
            }
        } catch (QueryException $qe) {
            $errCode = $qe->errorInfo[1];

            switch ($errCode) {
                case 1364:
                case 1048:
                    // Integrity constraint violation
                    $errMsg = trans('state::mysql.INTEGRITY_CONSTRAINT_VIOLATION', ['msg' => $qe->errorInfo[2]]);
                    break;
                case 1217:
                case 1451:
                    // Cannot delete or update a child row: a foreign key constraint fails
                    $errMsg = trans('state::mysql.CANNOT_DELETE_FOREIGN_KEY_CONSTRAINT_FAILS');
                    break;
                case 1216:
                case 1452:
                    // Cannot add or update a child row: a foreign key constraint fails
                    $errMsg = trans('state::mysql.CANNOT_ADD_FOREIGN_KEY_CONSTRAINT_FAILS');
                    break;
                case 1062:
                case 1586:
                    // Integrity constraint violation: Duplicate entry
                    if (preg_match("/^[^']*'([^']*)'[^']*'([^']*)'$/", $qe->errorInfo[2], $matches)) {
                        // Message in the form of: "Duplicate entry '%s' for key '%s'"
                        $errMsg = trans('state::mysql.DUPLICATE_ENTRY', ['value' => $matches[1], 'field' => $matches[2]]);
                    } else {
                        $errMsg = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_409');
                    }
                    break;
                case 1406:
                    // Data too long for column
                    if (preg_match("/[^column\s']?(\b[\w]*\b)['].*$/", $qe->errorInfo[2], $matches)) {
                        $errMsg = trans('state::mysql.DATA_TOO_LONG_FOR_COLUMN', ['field' => $matches[1]]);
                    } else {
                        $errMsg = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_500');
                    }
                    break;
                default:
                    $errMsg = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_503');
                    break;
            }
            $qeException = new ProjectException($qe->getMessage(), $errType, $errCode, null, $qe);
            $msg         = self::constructMessage($qeException, $errMsg, $errCode);

            if (is_callable($errorCatchCallable)) {
                $returnedException = new ProjectException($errMsg, $errType, $errCode, null, $qeException);
                return $errorCatchCallable($returnedException);
            } else {
                throw $qeException;
            }
        } catch (GuzzleException $ge) {
            $errCode     = 500;
            $errMsg      = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_500');
            $geException = new ProjectException($ge->getMessage(), $errType, $errCode, null, $ge);
            $msg         = self::constructMessage($geException, $errMsg, $errCode);

            if (is_callable($errorCatchCallable)) {
                return $errorCatchCallable($geException);
            } else {
                throw $geException;
            }
        } catch (ErrorException $ee) {
            $errCode     = 500;
            $errMsg      = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_500');
            $eeException = new ProjectException($ee->getMessage(), $errType, $errCode, null, $ee);
            $msg         = self::constructMessage($eeException, $errMsg, $errCode);

            if (is_callable($errorCatchCallable)) {
                return $errorCatchCallable($eeException);
            } else {
                throw $eeException;
            }
        } catch (Exception $e) {
            $errCode    = 500;
            $errMsg     = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_500');
            $eException = new ProjectException($e->getMessage(), $errType, $errCode, null, $e);
            $msg        = self::constructMessage($eException, $errMsg, $errCode);

            if (is_callable($errorCatchCallable)) {
                return $errorCatchCallable($eException);
            } else {
                throw $eException;
            }
        } catch (Throwable $e) {
            $errCode    = 500;
            $errMsg     = trans('state::generic.ERROR_MESSAGE_CODE_NUMBER_500');
            $eException = new ProjectException($e->getMessage(), $errType, $errCode, null, $e);
            $msg        = self::constructMessage($eException, $errMsg, $errCode);

            if (is_callable($errorCatchCallable)) {
                return $errorCatchCallable($eException);
            } else {
                throw $eException;
            }
        } finally {
            if (isset($msg) && !is_null($msg)) {
                Log::$errType($msg);
            }
        }

        return false;
    }

    /**
     * @param Throwable $exception
     * @param string    $errMsg - Custom error code
     * @param int       $errCode - Custom error message
     * @return string
     */
    static protected function constructMessage(Throwable $exception, $errMsg = '', $errCode = 0) {
        if ($exception instanceof CustomException) {
            $additionalData = $exception->getData(true);
        } else {
            $additionalData = '';
        }

        if (empty(trim($additionalData))) {
            $additionalData = 'Not available';
        }

        if ($exception->getPrevious()) {
            $previous = $exception->getPrevious();
            $message  = $previous->getMessage();
            $code     = $previous->getCode();
            $file     = $previous->getFile();
            $line     = $previous->getLine();
            $trace    = $previous->getTraceAsString();
        } else {
            $message = $exception->getMessage();
            $code    = $exception->getCode();
            $file    = $exception->getFile();
            $line    = $exception->getLine();
            $trace   = $exception->getTraceAsString();
        }

        $msg = '[StateGuard]'                           . "\r\n"
               . 'Custom Message: '   . $errMsg         . "\r\n"
               . 'Custom Code: '      . $errCode        . "\r\n"
               . 'Additional Data: '  . $additionalData . "\r\n"
               . 'Original Message: ' . $message        . "\r\n"
               . 'Original Code: '    . $code           . "\r\n"
               . 'File: '             . $file           . "\r\n"
               . 'Line: '             . $line           . "\r\n"
               . 'Stack Trace: '                        . "\r\n"
               . $trace                                 . "\r\n"
               .
               '<---------------------------------------------------------[O.o]--------------------------------------------------------->';

        return $msg;
    }
}
