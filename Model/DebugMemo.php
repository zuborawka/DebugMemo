<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
class DebugMemo extends AppModel {

    /**
     * Default E-mail address for sending the update result.
     * [priority]
     * Configure::read('DebugMemo.email_from') > $this->defaultEMailFrom
     *
     * @var string
     */
    public $defaultEMailFrom = 'debug_memo.notifier@default.com';

    /**
     * update
     */
    public function update($data) {
        if (empty($data[$this->alias])) {
            throw new InvalidArgumentException(__('Invalid Access'));
        }

        $args = array_merge(
            array(
                'plugin' => '',
                'controller' => '',
                'action' => '',
            ),
            $data[$this->alias]
        );

        extract($args);

        $current = $this->find('first', array(
                'conditions' => array(
                    $this->alias . '.plugin' => $plugin,
                    $this->alias . '.controller' => $controller,
                    $this->alias . '.action' => $action,
                )
            )
        );

        if (empty($current)) {
            $this->create();
        } else {
            // Not modified.
            if ($current[$this->alias]['memo'] === $data[$this->alias]['memo']) {
                return true;
            }
            $data[$this->alias]['id'] = $current[$this->alias]['id'];
        }
        $this->set($data);
        $result = $this->save(null, true);

        if ($result) {
            // Send mail
            try {
                $email = new CakeEmail('debug_memo');
                $from = $email->from();
                if (empty($from)) {
                    $mailFrom = Configure::read('DebugMemo.email_from');
                    if (empty($mailFrom)) {
                        $mailFrom = $this->defaultEMailFrom;
                    }
                    $email->from($mailFrom, 'DebugMemo Notifier');
                }
                $to = $email->to();

                $prefix = Configure::read('DebugMemo.email_subject_prefix');
                $subject = $email->subject();
                if (empty($subject)) {
                    $subject = 'Memo updated';
                }

                $url = self::urlString($result[$this->alias]);
                $email->subject($prefix . '['. date('Ymd H:i:s') . '][' . $url . '] ' . $subject);
                $msg = array(
                    $subject,
                    '',
                    '-------------------------------',
                    'Info:',
                    '-------------------------------',
                    '',
                    '* URL	   : ' . $url,
                    '* Modified  : ' . $result[$this->alias]['modified'],
                    '',
                    '-------------------------------',
                    'Memo:',
                    '-------------------------------',
                    '',
                    $result[$this->alias]['memo'],
                    '',
                );
                $email->send(join("\n", $msg));
            } catch (ConfigureException $e) {
                // Drop ConfigureException
            }
            $this->data = $result;
            return true;
        } else {
            throw new ValidationException();
        }
    }

/**
 * Returns a string composed by url arguments
 * [usage] $str = DebugMemo::urlString($this->request->params);
 *
 * @param array $args (plugin / controller / action)
 * @return string
 */
    public static function urlString($args = array()) {
        $args = array_merge(
            array(
                'plugin' => '',
                'controller' => '',
                'action' => '',
            ),
            $args
        );

        $url = $args['controller'] . '/' . $args['action'];

        if (!empty($args['plugin'])) {
            $url = $args['plugin'] . '/' . $url;
        }

        return $url;
    }

/**
 * Returns 'memo' field of the record specified arguments.
 * [usage] $memo = DebugMemo::readMemo($this->request->params);
 *
 *
 * @param array $args (plugin / controller / action)
 * @return string
 */
    public static function readMemo($args = array()) {
        $args = array_merge(
            array(
                'plugin' => '',
                'controller' => '',
                'action' => '',
            ),
            $args
        );

        $DebugMemo = ClassRegistry::init('DebugMemo.DebugMemo');

        $fields = 'memo';

        $conditions = array(
            'DebugMemo.plugin' => $args['plugin'],
            'DebugMemo.controller' => $args['controller'],
            'DebugMemo.action' => $args['action'],
        );

        $memo = $DebugMemo->find('first', compact('fields', 'conditions'));

        if (empty($memo['DebugMemo']['memo'])) {
            return '';
        }

        return $memo['DebugMemo']['memo'];
    }
}