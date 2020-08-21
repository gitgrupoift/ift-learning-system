<?php

$ld_moodle_rules = array(
    '<question type="multichoice">' => '<question answerType="single">',
    '/<name>(\s*)<text>/' => '<title><![CDATA[',
    '</text></name>' => ']]></title>'
);

$moodle_src = ' <question type="multichoice">
    <name>
      <text>1.10 - No planeamento do enchimento da caixa de carga do veículo é imperativo considerar-se:</text>
    </name>
    <questiontext format="html">
      <text><![CDATA[<p><b>1.10 - No planeamento do enchimento da caixa de carga do
veículo é imperativo considerar-se: </b><br></p>]]></text>
    </questiontext>
    <generalfeedback format="html">
      <text></text>
    </generalfeedback>
    <defaultgrade>1.0000000</defaultgrade>
    <penalty>0.3333333</penalty>
    <hidden>0</hidden>
    <idnumber></idnumber>
    <single>true</single>
    <shuffleanswers>true</shuffleanswers>
    <answernumbering>abc</answernumbering>
    <correctfeedback format="html">
      <text>A sua resposta está correta.</text>
    </correctfeedback>
    <partiallycorrectfeedback format="html">
      <text>A sua resposta está parcialmente correta.</text>
    </partiallycorrectfeedback>
    <incorrectfeedback format="html">
      <text>A sua resposta está incorreta.</text>
    </incorrectfeedback>
    <shownumcorrect/>
    <answer fraction="100" format="html">
      <text><![CDATA[<p>A carga
máxima admissível, determinada pelo peso ou cubicagem máxima admitida<br></p>]]></text>
      <feedback format="html">
        <text></text>
      </feedback>
    </answer>
    <answer fraction="0" format="html">
      <text><![CDATA[<p><p>A
disponibilização de equipamentos que facilitem o manuseamento e a carga das
mercadorias&nbsp; </p><br></p>]]></text>
      <feedback format="html">
        <text></text>
      </feedback>
    </answer>
    <answer fraction="0" format="html">
      <text><![CDATA[<p><p>O tipo de mercadoria a transportar, garantindo-se que
em cada veículo seja transportada carga com iguais características de peso e
dimensão&nbsp; </p><br></p>]]></text>
      <feedback format="html">
        <text></text>
      </feedback>
    </answer>
    <answer fraction="0" format="html">
      <text><![CDATA[<p><p>O espaço entre os lotes da mercadoria a fim de garantir o
equilíbrio do veículo&nbsp; </p><br></p>]]></text>
      <feedback format="html">
        <text></text>
      </feedback>
    </answer>
  </question>';


$ld_result = str_replace(
   array_keys($ld_moodle_rules),
    array_values($ld_moodle_rules),
    $moodle_src
);

$result = fopen("moodle.txt", "w") or die("Unable to open file!");
fwrite($result, $ld_result);
fclose($result);