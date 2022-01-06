<?php

namespace EZKnock;

class Options extends Resource {

    const QUALIFICATION_CATEGORY_STANDARD = 'standard';
    const QUALIFICATION_CATEGORY_COVERAGE = 'coverage';

    /**
     * Get list of qualifications
     * @see https://developers.ezknockmarketplace.com/reference#get-qualifications
     *
     * @return array
     */
    public function getQualifications($category = self::QUALIFICATION_CATEGORY_STANDARD) {
        return $this->client->get('/options/qualifications', [
            'category' => $category
        ]);
    }
}
