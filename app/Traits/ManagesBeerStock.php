<?php

namespace App\Traits;

trait ManagesBeerStock
{
    public function getStockData()
    {
        $path = storage_path('app/stock.json');
        if (!file_exists($path)) {
            $data = ['six' => 45, 'caguama' => 30];
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            file_put_contents($path, json_encode($data));
            return $data;
        }
        return json_decode(file_get_contents($path), true) ?: ['six' => 45, 'caguama' => 30];
    }

    public function updateStockData($six, $caguama)
    {
        $path = storage_path('app/stock.json');
        $data = ['six' => max(0, $six), 'caguama' => max(0, $caguama)];
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, json_encode($data));
    }

    public function loadStock()
    {
        $stock = $this->getStockData();
        $this->stockSix = $stock['six'];
        $this->stockCaguama = $stock['caguama'];

        if ($this->stockSix <= 0) {
            $this->orderSix = false;
            $this->sixQty = 0;
        } else {
            if ($this->sixQty > $this->stockSix) {
                $this->sixQty = $this->stockSix;
            }
        }

        if ($this->stockCaguama <= 0) {
            $this->orderCaguama = false;
            $this->caguamaQty = 0;
        } else {
            if ($this->caguamaQty > $this->stockCaguama) {
                $this->caguamaQty = $this->stockCaguama;
            }
        }
    }
}
