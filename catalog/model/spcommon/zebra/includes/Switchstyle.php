<?php

class Zebra_Form_Switchstyle extends Zebra_Form_Control
{

    function __construct($id, $default = '')
    {

        // call the constructor of the parent class
        parent::__construct();

        // set the private attributes of this control
        // these attributes are private for this control and are for internal use only
        // and will not be rendered by the _render_attributes() method
        $this->private_attributes = array(

            'disable_xss_filters',
            'locked',

        );

        // set the default attributes for the hidden control
        // put them in the order you'd like them rendered

        // notice that if control's name is 'MAX_FILE_SIZE' we'll generate a random ID attribute for the control
        // as, with multiple forms having upload controls on them, this hidden control appears as many times as the
        // forms do and we don't want to have the same ID assigned to multiple controls
        $this->set_attributes(

            array(

                'type'  =>  'switchstyle',
                'name'  =>  $id,
                'id'    =>  ($id != 'MAX_FILE_SIZE' ? $id : 'mfs_' . rand(0, 100000)),
                'value' =>  $default,

            )

        );

    }

    /**
     *  Generates the control's HTML code.
     *
     *  <i>This method is automatically called by the {@link Zebra_Form::render() render()} method!</i>
     *
     *  @return string  The control's HTML code
     */
    function toHTML()
    {

        return '';

    }

}

?>
