<?php

class CFormatterExt extends CFormatter {

    /**
     * 格式化年月
     * @param integer $value
     * @return mixed
     */
    public function formatYearMonth($value) {
        if (!empty($value)) {
            return substr($value, 0, 4) . '-' . sprintf('%02d', (int) substr($value, 4, 2));
        } else {
            return null;
        }
    }

    /**
     * 格式化数字
     * @param mixed $value
     * @return mixed
     */
    public function formatNumber($value) {
        return ($value) ? parent::formatNumber($value) : null;
    }

    /**
     * Issue kind
     * @param integer $value
     * @return mixed
     */
    public function formatKind($value) {
        if ($value) {
            $options = Issue::kindOptions();
            return isset($options[$value]) ? $options[$value] : null;
        } else {
            return null;
        }
    }

    /**
     * Issue progress
     * @param integer $value
     * @return mixed
     */
    public function formatProgress($value) {
        $options = Issue::progressOptions();
        return isset($options[$value]) ? $options[$value] : null;
    }

    /**
     * Issue progress cssClass
     * @param integer $value
     * @return mixed
     */
    public function formatProgressCssClass($value) {
        switch ($value) {
            case Issue::PROGRESS_WAITING:
                return 'waiting';
                break;
            case Issue::PROGRESS_TODO:
                return 'todo';
                break;
            case Issue::PROGRESS_IN_PROGRESS:
                return 'in-progress';
                break;
            case Issue::PROGRESS_DONE:
                return 'done';
                break;
            default :
                return null;
                break;
        }
    }
    
    /**
     * Issue kind char
     * @param integer $value
     * @return mixed
     */
    public function formatKindLetter($value) {
        switch ($value) {
            case Issue::KIND_BUG:
                return 'B';
                break;
            case Issue::KIND_ENHANCEMENT:
                return 'E';
                break;
            case Issue::KIND_PROPOSAL:
                return 'P';
                break;
            case Issue::KIND_TASK:
                return 'T';
                break;
            default :
                return null;
                break;
        }
    }
    

    /**
     * Issue priority
     * @param integer $value
     * @return mixed
     */
    public function formatPriority($value) {
        if ($value) {
            $options = Issue::priorityOptions();
            return isset($options[$value]) ? $options[$value] : null;
        } else {
            return null;
        }
    }

}