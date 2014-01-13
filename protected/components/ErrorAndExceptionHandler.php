<?php
/**
 * Обработчик ошибок и исключений
 */

class ErrorAndExceptionHandler extends CComponent
{
    public static function handleError(CEvent $Event)
    {
        // если вдруг метод вызвали не при ошибке
        if($error = Yii::app()->errorHandler->error)
        {
            self::sendErrorHeader(500);

            if (defined('YII_DEBUG') && YII_DEBUG === true) {

                if (Yii::app()->request->isAjaxRequest) {
                    echo $error['message'];
                } else {
                    Yii::app()->controller->render('application.views.common.errorCustomAndTrace',$error);
                }

            } else {

                if (Yii::app()->request->isAjaxRequest) {
                    echo Yii::t('common', 'Произошла ошибка!');
                } else {
                    Yii::app()->controller->render('application.views.common.errorStandart');
                }
            }
        }

        // чтобы yii не обрабатывал ошибку дальше своими средствами
        $Event->handled = true;
    }

    public static function handleException(CEvent $Event)
    {
        // если вдруг метод вызвали не при ошибке
        if($error = Yii::app()->errorHandler->error)
        {
            if ($error['type'] == 'CHttpException') {

                self::sendErrorHeader($error['code']);

                if (defined('YII_DEBUG') && YII_DEBUG === true) {

                    if (Yii::app()->request->isAjaxRequest) {
                        echo $error['message'];
                    } else {
                        Yii::app()->controller->render('application.views.common.errorCustomAndTrace',$error);
                    }

                } else {

                    if (Yii::app()->request->isAjaxRequest) {
                        echo $error['message'];
                    } else {
                        Yii::app()->controller->render('application.views.common.errorCustom', $error);
                    }
                }
            } else {

                self::sendErrorHeader(500);

                if (defined('YII_DEBUG') && YII_DEBUG === true) {

                    if (Yii::app()->request->isAjaxRequest) {
                        echo $error['message'];
                    } else {
                        Yii::app()->controller->render('application.views.common.errorCustomAndTrace',$error);
                    }

                } else {

                    if (Yii::app()->request->isAjaxRequest) {
                        echo Yii::t('common', 'Произошла ошибка!');
                    } else {
                        Yii::app()->controller->render('application.views.common.errorStandart');
                    }
                }
            }
        }

        // чтобы yii не обрабатывал исключение дальше своими средствами
        $Event->handled = true;
    }

    /**
     * Посылает заголовок с кодом ошибки
     * @param mixed $error или массив с данными ошибки, или код ошибки
     */
    protected static function sendErrorHeader($error)
    {
        $errorCode = null;
        if (is_array($error)) {
            $errorCode = $error['code'];
        } else {
            $errorCode = $error;
        }

        if (!headers_sent()) {
            header("HTTP/1.0 {$errorCode} ".self::getHttpHeader($errorCode));
        }
    }

    /**
     * Return correct message for each known http error code
     * @param integer $httpCode error code to map
     * @param string $replacement replacement error string that is returned if code is unknown
     * @return string the textual representation of the given error code or the replacement string if the error code is unknown
     */
    protected static function getHttpHeader($httpCode, $replacement='')
    {
        $httpCodes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            118 => 'Connection timed out',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            210 => 'Content Different',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            310 => 'Too many Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested range unsatisfiable',
            417 => 'Expectation failed',
            418 => 'I’m a teapot',
            422 => 'Unprocessable entity',
            423 => 'Locked',
            424 => 'Method failure',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway ou Proxy Error',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
            507 => 'Insufficient storage',
            509 => 'Bandwidth Limit Exceeded',
        );
        if(isset($httpCodes[$httpCode]))
            return $httpCodes[$httpCode];
        else
            return $replacement;
    }

} 