<?php


namespace App\Http\Requests\Factory;

class FileRequest extends BaseRequest
{
    public function unsetUnchanged(): void
    {
    }

    /**
     * Remove empty file elements from files array
     */
    public function unsetEmpty(): void
    {
        foreach ($this->unset_empty_attrs as $empty_attr_key) {
            $attr = $this->request->get($empty_attr_key);
            $attr_type = gettype($attr);

            if ($attr_type === 'array') {
                foreach ($attr as $file_key => $file) {
                    if (empty($attr[0]) || $attr[0] === 'null') {
                        unset($attr[$file_key]);
                    }
                }

                if (empty($attr)) {
                    $this->request->remove($empty_attr_key);
                }
                else {
                    $this->request->set($empty_attr_key, $attr);
                }
            }
        }
    }
}
