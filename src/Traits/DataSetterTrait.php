<?php

namespace RvltDigital\SymfonyRevoltaBundle\Traits;

trait DataSetterTrait
{
    /**
     * @param  array $data
     * @param  array|null $fnList Optional whitelist or blacklist of functions that could or not be called
     * @param  bool $isFnListBlacklist Indicates if parameter $fnList is blacklist or whitelist
     * @return static
     */
    public function setData(array $data, array $fnList = null, bool $isFnListBlacklist = true)
    {
        if ($fnList !== null) {
            $fnList = array_map('strtolower', $fnList);
        }
        foreach ($data as $key => $value) {
            $key = explode('_', $key);
            $method = 'set' . strtolower(implode('', $key));
            if (!empty($fnList) &&
                (($isFnListBlacklist && in_array($method, $fnList, true)) ||
                    (!$isFnListBlacklist && !in_array($method, $fnList, true)))
            ) {
                continue;
            }
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }
}
