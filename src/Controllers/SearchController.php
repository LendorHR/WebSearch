<?php
/**
 * Created by PhpStorm.
 * User: hoffmann
 * Date: 09.02.18
 * Time: 9:51
 */

namespace App\Controllers;


use App\Core\Controller;

class SearchController extends Controller
{
    public function googleAction()
    {
        if (property_exists($this->configs->search_engines, 'google')) {
            if (key_exists('search_query', $_GET)) {
                $this->searchQueryInGoogle((string)$_GET['search_query']);
            }
            $this->view->render('search/index.phtml');
        } else {
            header('Location:/404');
        }
    }

    private function searchQueryInGoogle(string $query)
    {
        $url = "https://www.googleapis.com/customsearch/v1" .
            "?key=" . $this->configs->search_engines->google->api_key .
            "&cx=" . $this->configs->search_engines->google->custom_search_cx .
            "&num=" . $this->configs->global->number_of_results .
            "&q=" . urlencode($query);

        if ($json_data = $this->sendQueryToApi($url)) {
            $json_data = str_replace(['\u003cb\u003e', '\u003c/b\u003e'], ["<mark>", "</mark>"], $json_data);
            $result = json_decode($json_data);
            if (property_exists($result, "error")) {
                $this->handleGoogleErrors($result);
            } else {
                if ($result->searchInformation->totalResults == "0") {
                    if ($this->searchCorrectedQuery($result)) {
                        return true;
                    } else {
                        $this->view->data->info_message = "On your request nothing has been found.";
                        return false;
                    }
                } else {
                    foreach ($result->items as $item) {
                        $this->buildDataItems($item->htmlTitle, $item->link, $item->htmlSnippet);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    private function handleGoogleErrors(\stdClass $response)
    {
        if ($response->error->code != 200) {
            if ($response->error->errors[0]->reason == "dailyLimitExceeded") {
                $this->view->data->error_message = "<strong>Query error:</strong> Exceeded the limit of requests per day.";
                return;
            } else {
                $this->view->data->error_message = "<strong>Query error:</strong> Incorrectly constructed query url.";
                return;
            }
        }
    }

    private function searchCorrectedQuery(\stdClass $result) {
        if (property_exists($result, "spelling")) {
            if ($this->searchQueryInGoogle($result->spelling->correctedQuery)) {
                $this->view->data->info_message = "On your request nothing has been found. Showing results on <strong>". $result->spelling->correctedQuery ."</strong>.";
                return true;
            }
        }
        return false;
    }

    public function yandexAction()
    {
        if (property_exists($this->configs->search_engines, 'yandex')) {
            if (key_exists('search_query', $_GET)) {
                $this->searchQueryInYandex((string)$_GET['search_query']);
            }
            $this->view->render('search/index.phtml');
        } else {
            header('Location:/404');
        }
    }

    private function searchQueryInYandex(string $query) {
        $url = "https://yandex.com/search/xml" .
            "?user=" . $this->configs->search_engines->yandex->user_name .
            "&key=" . $this->configs->search_engines->yandex->api_key .
            "&query=" . urlencode($query) .
            "&l10n=" . "en" .
            "&sortby=" . "rlv" .
            "&filter=" . "strict" .
            "&groupby=" .
                "attr%3D" . "%22%22" .
                ".mode%3D" . "flat" .
                ".groups-on-page%3D" . $this->configs->global->number_of_results .
                ".docs-in-group%3D" . 1;

        if ($xml_data = $this->sendQueryToApi($url)) {
            $xml_data = str_replace(["<hlword>", "</hlword>"], ["[mark]", "[/mark]"], $xml_data);

            $json_data = json_encode(simplexml_load_string($xml_data));
            $json_data = str_replace(["[mark]", "[\\/mark]"], ["<mark>", "</mark>"], $json_data);
            $result = json_decode($json_data);

            if (property_exists($result->response, "error")) {
                $this->handleYandexErrors($result);
            } else {
                foreach ($result->response->results->grouping->group as $item) {
                    if (property_exists($item->doc, "headline")) {
                        $text = $item->doc->headline;
                    } else {
                        if (is_array($item->doc->passages->passage)) {
                            $text = implode(" ... ", $item->doc->passages->passage);
                        } else {
                            $text = $item->doc->passages->passage;
                        }
                    }
                    $this->buildDataItems($item->doc->title, $item->doc->url, $text);
                }
                return true;
            }
        }
        return false;
    }

    private function handleYandexErrors(\stdClass $query)
    {
        if ($query->response->error) {
            if ($query->response->error = "Sorry, there are no results for this search") {
                $this->view->data->info_message = $query->response->error . ".";
                return;
            } else {
                $this->view->data->error_message = "<strong>Query error:</strong> Incorrectly constructed query url.";
                return;
            }
        }
    }

    private function sendQueryToApi(string $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!($response_data = curl_exec($curl))) {
            $this->view->data->error_message = "<strong>cURL error:</strong> " . curl_error($curl);
            return false;
        }
        curl_close($curl);

        return $response_data;
    }

    private function buildDataItems(string $title, string $link, string $text)
    {
        $this->view->data->items[] = (object)[
            "title" => $title,
            "link" => $link,
            "text" => $text
        ];
    }
}