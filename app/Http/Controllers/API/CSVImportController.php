<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StatisticHourly;
use App\StatisticDaily;
use App\StatisticWeekly;
use App\StatisticMonthly;
use App\StatisticQuarterly;

class CSVImportController extends Controller
{
    public function loadHourlyDataFromCSV() {
        $fileName = '/home/developer/www/api-grip-investments/hourly.csv';
        if (!file_exists($fileName)) {
            echo "No file '{$fileName}' was found!";
        }

        if (($handle = fopen($fileName, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ' ')) !== false) {
                $record = new StatisticHourly();
                $record->grip_date = date("Y-m-d H:i:s", strtotime($data[0]));
                $record->open = $data[1];
                $record->high = $data[2];
                $record->low = $data[3];
                $record->last = $data[4];
                $record->save();
            }

            fclose($handle);
            echo "Import was successfully completed!";
        }
    }

    public function loadDailyDataFromCSV() {
        $fileName = '/home/developer/www/api-grip-investments/daily.csv';
        if (!file_exists($fileName)) {
            echo "No file '{$fileName}' was found!";
        }

        if (($handle = fopen($fileName, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ' ')) !== false) {
                $record = new StatisticDaily();
                $record->grip_date = date("Y-m-d H:i:s", strtotime($data[0]));
                $record->open = $data[1];
                $record->high = $data[2];
                $record->low = $data[3];
                $record->last = $data[4];
                $record->save();
            }

            fclose($handle);
            echo "Import was successfully completed!";
        }
    }

    public function loadWeeklyDataFromCSV() {
        $fileName = '/home/developer/www/api-grip-investments/weekly.csv';
        if (!file_exists($fileName)) {
            echo "No file '{$fileName}' was found!";
        }

        if (($handle = fopen($fileName, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ' ')) !== false) {
                $record = new StatisticWeekly();
                $record->grip_date = date("Y-m-d H:i:s", strtotime($data[0]));
                $record->open = $data[1];
                $record->high = $data[2];
                $record->low = $data[3];
                $record->last = $data[4];
                $record->save();
            }

            fclose($handle);
            echo "Import was successfully completed!";
        }
    }

    public function loadMonthlyDataFromCSV() {
        $fileName = '/home/developer/www/api-grip-investments/monthly.csv';
        if (!file_exists($fileName)) {
            echo "No file '{$fileName}' was found!";
        }

        if (($handle = fopen($fileName, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ' ')) !== false) {
                $record = new StatisticMonthly();
                $record->grip_date = date("Y-m-d H:i:s", strtotime($data[0]));
                $record->open = $data[1];
                $record->high = $data[2];
                $record->low = $data[3];
                $record->last = $data[4];
                $record->save();
            }

            fclose($handle);
            echo "Import was successfully completed!";
        }
    }

    public function loadQuarterlyDataFromCSV() {
        $fileName = '/home/developer/www/api-grip-investments/quarterly.csv';
        if (!file_exists($fileName)) {
            echo "No file '{$fileName}' was found!";
        }

        if (($handle = fopen($fileName, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ' ')) !== false) {
                $record = new StatisticQuarterly();
                $record->grip_date = date("Y-m-d H:i:s", strtotime($data[0]));
                $record->open = $data[1];
                $record->high = $data[2];
                $record->low = $data[3];
                $record->last = $data[4];
                $record->save();
            }

            fclose($handle);
            echo "Import was successfully completed!";
        }
    }

    public function importCSV() {
        // $this->loadHourlyDataFromCSV();
        // $this->loadDailyDataFromCSV();
        $this->loadWeeklyDataFromCSV();
        $this->loadMonthlyDataFromCSV();
        $this->loadQuarterlyDataFromCSV();
    }
}
