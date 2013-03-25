<?php

    /**
     * A class to use the API of pingen.com as an integrator
     *
     * For more information about Pingen and how to use it as an integrator see
     * https://pingen.com/en/customer/integrator/Briefversand-für-Integratoren.html
     *
     * API documentation can be found here:
     * https://www.pingen.com/en/developer.html
     *
     *
     * Copyright (c) 2013 by Pingen.com
     * Permission is hereby granted, free of charge, to any person obtaining a
     * copy of this software and associated documentation files (the "Software"),
     * to deal in the Software without restriction, including without limitation
     * the rights to use, copy, modify, merge, publish, distribute, sublicense,
     * and/or sell copies of the Software, and to permit persons to whom the
     * Software is furnished to do so, subject to the following conditions:
     * The above copyright notice and this permission notice shall be included
     * in all copies or substantial portions of the Software.
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
     * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
     * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
     * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
     * OR OTHER DEALINGS IN THE SOFTWARE.
     *
     * @link https://www.pingen.com/en/developer.html
     */


    class Pingen
    {
        /**
         * @var string Base URL of Pingen API
         */
        protected $sBaseURL = 'https://dev-api.pingen.com';

        /**
         * @var string Auth token
         */
        private $sToken;

        /**
         * Constructor of class
         *
         * @param string $sToken Auth token
         * @param string $sConnectionMethod Connection method
         * @throws Exception Wrong connection method
         */
        public function __construct($sToken)
        {
            $this->sToken = $sToken;
        }

        /**
         * You can list your available documents
         *
         * See https://www.pingen.com/en/developer/endpoints-documents.html
         *
         * @param int $iLimit Limit the amount of results
         * @param int $iPage When limiting the results, specifies page
         * @param string $sSort Sorts the list by the available values
         * @param string $sSortType Defines the way of sorting
         * @return object
         */
        public function documents_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc')
        {
            return $this->execute("document/list/" . ($iLimit ? "limit/$iLimit/" : "") . "page/$iPage/sort/$sSort/sorttype/$sSortType");
        }

        /**
         * Get information about a specific document
         *
         * See https://www.pingen.com/en/developer/endpoints-documents.html for available options
         *
         * @param int $iDocumentId
         * @return object
         */
        public function documents_get($iDocumentId)
        {
            return $this->execute("document/get/id/$iDocumentId");
        }

        /**
         * Download a specific document as pdf
         *
         * See https://www.pingen.com/en/developer/endpoints-documents.html for available options
         *
         * @param int $iDocumentId
         * @return application/pdf
         */
        public function documents_pdf($iDocumentId)
        {
            return $this->execute("document/pdf/id/$iDocumentId");
        }

        /**
         * Preview a specific document as png
         *
         * See https://www.pingen.com/en/developer/endpoints-documents.html for available options
         *
         * @param int $iDocumentId Document id
         * @param int $iPage Number of page that will be grabbed
         * @param int $iSize Withd of preview
         * @return image/png
         */
        public function documents_preview($iDocumentId, $iPage = 1, $iSize = 595)
        {
            return $this->execute("document/preview/id/$iDocumentId/page/$iPage/size/$iSize");
        }

        /**
         * Delete a specific document
         *
         * @param int $iDocumentId
         * @return object
         */
        public function documents_delete($iDocumentId)
        {
            return $this->execute("document/delete/id/$iDocumentId");
        }

        /**
         * Send a specific document
         *
         * See https://www.pingen.com/en/developer/endpoints-documents.html for available options
         *
         * @param int $iDocumentId
         * @param int $iSpeed
         * @param int $iColor
         * @return object
         */
        public function documents_send($iDocumentId, $iSpeed = 1, $iColor = 1)
        {
            $aData = array('speed' => $iSpeed, 'color' => $iColor);
            return $this->execute("document/send/id/$iDocumentId", $aData);
        }

        /**
         * Upload a new file (and optionally send it right away)
         *
         * See https://www.pingen.com/en/developer/endpoints-documents.html for available options
         *
         * @param string $sFile
         * @param array $aOptions
         * @return object
         */
        public function documents_upload($sFile, $aOptions = array())
        {
            return $this->execute('document/upload', $aOptions, $sFile);
        }

        /**
         * You can list your available letters
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLimit Limit the amount of results
         * @param int $iPage When limiting the results, specifies page
         * @param string $sSort Sorts the list by the available values
         * @param string $sSortType Defines the way of sorting
         * @return object
         */
        public function letters_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc')
        {
            return $this->execute("letter/list/" . ($iLimit ? "limit/$iLimit/" : "") . "page/$iPage/sort/$sSort/sorttype/$sSortType");
        }

        /**
         * You can get your letter object
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLetterId The Id of the letter
         * @return object
         */
        public function letters_get($iLetterId)
        {
            return $this->execute("letter/get/id/$iLetterId");
        }

        /**
         * You can add new letter
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param array $aData Body parameters
         * @return object
         */
        public function letters_add($aData)
        {
            return $this->execute("letter/add", $aData);
        }

        /**
         * You can edit letter
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLetterId The id of the letter
         * @param array $aData Body Parameters
         * @return object
         */
        public function letters_edit($iLetterId, $aData)
        {
            return $this->execute("letter/edit/id/$iLetterId", $aData);
        }

        /**
         * You can get letter preview
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLetterId The id of the letter
         * @param int $iPage The page of the letter to grab as preview
         * @param int $iSize The width of preview
         * @return application/image
         */
        public function letters_preview($iLetterId, $iPage = 1, $iSize = 595)
        {
            return $this->execute("letter/preview/id/$iLetterId/page/$iPage/size/$iSize");
        }

        /**
         * You can get letter as pdf
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLetterId The id of the letter
         * @return application/pdf
         */
        public function letters_pdf($iLetterId)
        {
            return $this->execute("letter/pdf/id/$iLetterId");
        }

        /**
         * You can send letter
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLetterId The id of the letter
         * @param int $iSpeed
         * @param int $iColor
         * @return object
         */
        public function letters_send($iLetterId, $iSpeed = 1, $iColor = 1)
        {
            $aData = array('speed' => $iSpeed, 'color' => $iColor);
            return $this->execute("letter/send/id/$iLetterId", $aData);
        }

        /**
         * You can delete letter
         *
         * See https://www.pingen.com/en/developer/endpoints-letters.html
         *
         * @param int $iLetterId The id of the letter
         * @return object
         */
        public function letters_delete($iLetterId)
        {
            return $this->execute("letter/delete/id/$iLetterId");
        }

        /**
         * You can list your available post sends
         *
         * See https://www.pingen.com/en/developer/endpoints-posts.html
         *
         * @param int $iLimit Limit the amount of results
         * @param int $iPage When limiting the results, specifies page
         * @param string $sSort Sorts the list by available values
         * @param string $sSortType Defines the way of sorting
         * @return object
         */
        public function posts_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc')
        {
            return $this->execute("post/list/" . ($iLimit ? "limit/$iLimit/" : "") . "page/$iPage/sort/$sSort/sorttype/$sSortType");
        }

        /**
         * You can get your post object
         *
         * See https://www.pingen.com/en/developer/endpoints-posts.html
         *
         * @param int $iPostId The Id of the post sending
         * @return object
         */
        public function posts_get($iPostId)
        {
            return $this->execute("post/get/id/$iPostId");
        }

        /**
         * You can cancel post
         *
         * See https://www.pingen.com/en/developer/endpoints-posts.html
         *
         * @param int $iPostId The Id of the post sending
         * @return object
         */
        public function posts_cancel($iPostId)
        {
            return $this->execute("post/cancel/id/$iPostId");
        }

        /**
         * You can list your queue
         *
         * See https://www.pingen.com/en/developer/endpoints-queue.html
         *
         * @param int $iLimit Limit the amount of results
         * @param int $iPage When limiting the results, specifies page
         * @param string $sSort Sorts the list by available values
         * @param string $sSortType Defines the way of sorting
         * @return object
         */
        public function queue_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc')
        {
            return $this->execute("queue/list/" . ($iLimit ? "limit/$iLimit/" : "") . "page/$iPage/sort/$sSort/sorttype/$sSortType");
        }

        /**
         * You can get your queue
         *
         * See https://www.pingen.com/en/developer/endpoints-queue.html
         *
         * @param int $iQueueId The Id of the queue entry
         * @return object
         */
        public function queue_get($iQueueId)
        {
            return $this->execute("queue/get/id/$iQueueId");
        }

        /**
         * You can cancel a pending queue entry
         *
         * See https://www.pingen.com/en/developer/endpoints-queue.html
         *
         * @param int $iQueueId The Id of the queue entry
         * @param array $aData Body Parameters
         * @return object
         */
        public function queue_cancel($iQueueId, $aData = array())
        {
            return $this->execute("queue/cancel/id/$iQueueId", $aData);
        }

        /**
         * You can list your available contacts
         *
         * See https://www.pingen.com/en/developer/endpoints-contacts.html
         *
         * @param int $iLimit Limit the amount of results
         * @param int $iPage When limiting the results, specifies page
         * @param string $sSort Sorts the list by available values
         * @param string $sSortType Defines the way of sorting
         * @return object
         */
        public function contacts_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc')
        {
            return $this->execute("contact/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType");
        }

        /**
         * You can get your document
         *
         * See https://www.pingen.com/en/developer/endpoints-contacts.html
         *
         * @param int $iContactId The Id of the contact
         * @return object
         */
        public function contacts_get($iContactId)
        {
            return $this->execute("contact/get/id/$iContactId");
        }

        /**
         * You can add new contact
         *
         * See https://www.pingen.com/en/developer/endpoints-contacts.html
         *
         * @param array $aData Body parameters
         * @return object
         */
        public function contacts_add($aData)
        {
            return $this->execute("contact/add", $aData);
        }

        /**
         * You can edit new contact
         *
         * See https://www.pingen.com/en/developer/endpoints-contacts.html
         *
         * @param int $iContactId The Id of the contact
         * @param array $aData Body parameters
         * @return object
         */
        public function contacts_edit($iContactId, $aData)
        {
            return $this->execute("contact/edit/id/$iContactId", $aData);
        }

        /**
         * You can delete a contact
         *
         * See https://www.pingen.com/en/developer/endpoints-contacts.html
         *
         * @param int $iContactId The Id of the contact
         * @return object
         */
        public function contacts_delete($iContactId)
        {
            return $this->execute("contact/delete/id/$iContactId");
        }

        /**
         * You can calculate fax sending
         *
         * @param string $sNumber Fax number starting with country code and plus at beginning
         * @param int $iPages Number of pages per document
         * @param int $iDocuments Number of documents
         * @param string $sCurrency Currency of calculation
         * @return object
         */
        public function calculator_fax($sNumber, $iPages = 1, $iDocuments = 1, $sCurrency = 'CHF')
        {
            return $this->execute("calculator/fax/number/$sNumber/pages/$iPages/documents/$iDocuments/currency/$sCurrency");
        }

        /**
         * You can calculate post sending
         *
         * @param string $sCountry Country code for sending
         * @param int $iPrint Print option for black/color
         * @param int $iSpeed Speed option for normal/express
         * @param int $iPlan Your plan
         * @param int $iDocuments Number of documents
         * @param string $sCurrency Currency of payment
         * @param int $iPagesNormal Number of normal pages
         * @param int $iPagesESR Number of ESR pages
         * @return object
         */
        public function calculator_post($sCountry = 'CH', $iPrint = 1, $iSpeed = 1, $iPlan = 1, $iDocuments = 1, $sCurrency = 'CHF', $iPagesNormal = 0, $iPagesESR = 0)
        {
            return $this->execute("calculator/get/country/$sCountry/print/$iPrint/speed/$iSpeed/plan/$iPlan/documents/$iDocuments/currency/$sCurrency/pages_normal/$iPagesNormal/pages_esr/$iPagesESR");
        }

        /**
         * Grabbing your current credit value
         *
         * @return object
         */
        public function account_credit()
        {
            return $this->execute("account/credit");
        }

        /**
         * Grabbing your actual plan
         *
         * @return object
         */
        public function account_plan()
        {
            return $this->execute("account/plan");
        }

        /**
         * @param string $sKeyword
         * @param array $aData
         * @return object
         */
        private function execute($sKeyword, $aBodyParameters = array(), $sFile = false)
        {
            /* put together parameters */
            $aData = array();
            $aData['data'] = json_encode($aBodyParameters);
            if ($sFile) $aData['file'] = '@' . $sFile;

            /* prepare URL */
            $aURLParts = array(
                $this->sBaseURL,
                $sKeyword,
                'token',
                $this->sToken
            );
            $sURL = implode('/', $aURLParts);

            /* data may not be empty */
            if (isset($aData['data']) && (count(json_decode($aData['data'])) == 0 || $aData['data'] == ''))
            {
                unset($aData['data']);
            }

            $jsonResponse = false;

            try
            {
                $objCurlConn = curl_init();
                curl_setopt($objCurlConn, CURLOPT_URL, $sURL);
                curl_setopt($objCurlConn, CURLOPT_POST, 1);
                curl_setopt($objCurlConn, CURLOPT_POSTFIELDS, $aData);
                curl_setopt($objCurlConn, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($objCurlConn, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($objCurlConn, CURLOPT_SSL_VERIFYPEER, 0);
                $mResponse = curl_exec($objCurlConn);
            } catch (Exception $e)
            {
                throw new Exception("Error occurred in curl connection");
            }

            /* if PDF or Image, output plain result */
            if (substr($mResponse, 0, 4)=='%PDF' || substr($mResponse, 1, 3)=='PNG')
            {
                return $mResponse;
            }

            $objResponse = json_decode($mResponse);
            if ($objResponse->error)
            {
                throw new Exception($objResponse->errormessage, $objResponse->errorcode);
            }
            else
            {
                return $objResponse;
            }
        }
    }