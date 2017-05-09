<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A forma principal de configuração de novo módulo
  *
  * Usa o núcleo padrão Moodle formslib. Para mais informações sobre eles, por favor
  * Visite: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_sepex_mod_form extends moodleform_mod {

    /**Define os elementos do formulário*/
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Adicionando o campo "geral", onde todas as configurações comuns são mostradas.
        $mform->addElement('header', 'general', get_string('general', 'form'));

       // Adicionando o campo padrão "name".
        $mform->addElement('text', 'name', get_string('sepexname', 'sepex'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'sepexname', 'sepex');

        // Adicionando os campos padrão "intro" e "introformat".
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // --------------------DATA PARA INICIO DO EVENTO-------------------------
        $mform->addElement('header', 'availibilityhdr', get_string('availability'));

        $mform->addElement('date_time_selector', 'timeavailablefrom', get_string('availablefromdate', 'data'),
                           array('optional' => true));

        $mform->addElement('date_time_selector', 'timeavailableto', get_string('availabletodate', 'data'),
                           array('optional' => true));

       // ----------------------------------------------------------------------

       // Adicionar elementos de classificação padrão.
        $this->standard_grading_coursemodule_elements();

        // Adicionar elementos padrão, comuns a todos os módulos.
        $this->standard_coursemodule_elements();

       // Adicionar botões padrão, comuns a todos os módulos.
        $this->add_action_buttons();
    }
    
    /**
    * Aplicar regras de validação aqui
      * @param array $ data array de ("fieldname" => value) dos dados enviados
      * @param array $ files array de arquivos carregados "element_name" => tmp_file_path
      * @return array
     **/
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Verifique se as horas de abertura e fechamento são consistentes.
        if ($data['timeavailablefrom'] && $data['timeavailableto'] &&
                $data['timeavailableto'] < $data['timeavailablefrom']) {
            $errors['timeavailableto'] = get_string('availabletodatevalidation', 'data');
        }
        return $errors;
    }
}
