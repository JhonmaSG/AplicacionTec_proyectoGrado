<?php
require_once '../assets/php/Auth.php';
require_once '../../app/config/ConfiguracionBD/ConexionBD.php';
require_once '../../app/config/Logger.php';
Logger::registrarAccesoModulo($conn, 'Objetivos_RA');
Auth::iniciarSesion();
Auth::redirigirSiNoAutenticado();
Auth::evitarCache();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetivos de Programa y Resultados de Aprendizaje</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../assets/css/styles_menu.css">
</head>

<body>

    <?php include '../assets/php/menu.php'; ?>

    <div class="container">
        <h1>Objetivos de Programa y Resultados de Aprendizaje</h1>
        <table id="objectives-table">
            <thead>
                <tr>
                    <th>Objetivos de Programa</th>
                </tr>
            </thead>
            <tbody>
                <tr class="objective-row" data-id="1">
                    <td>OP1) Aplicar fundamentos matemáticos para resolver problemas complejos en el ámbito de la ingeniería y la tecnología, promoviendo el desarrollo de soluciones innovadoras que atiendan las necesidades del entorno y contribuyan a la sostenibilidad y el avance de la sociedad.</td>
                </tr>
                <tr class="results-row" id="results-1">
                    <td colspan="1">
                        <ul>
                            <li>R1: Aplica modelos matemáticos para resolver problemas de ingeniería en soluciones telemáticas, considerando restricciones técnicas y de eficiencia</li>
                            <li>R13: Aplica técnicas matemáticas para analizar datos en el contexto de soluciones telemáticas</li>
                            <li>R12: Evalúa problemas de complejidad algorítmica para optimizar soluciones en ingeniería telemática.
                            </li>
                            <li>R9: Desarrolla algoritmos para la solución de problemas de ingeniería.
                            </li>

                        </ul>
                    </td>
                </tr>


                <tr class="objective-row" data-id="2">
                    <td>OP2) Desarrollar soluciones de software innovadoras que respondan a las necesidades del entorno, promoviendo la eficiencia, la escalabilidad y la sostenibilidad, a través de la aplicación de principios de ingeniería y buenas prácticas en el ciclo de vida del software.</td>
                </tr>
                <tr class="results-row" id="results-2">
                    <td colspan="1">
                        <ul>
                            <li>R2: Diseña software para entornos de nube, teniendo en cuenta escalabilidad, seguridad y eficiencia</li>
                            <li>R7: Procesa datos para diseñar sistemas con arquitecturas de software</li>
                            <li>R8: Construye sistemas informáticos para satisfacer las necesidades del entorno</li>
                            <li>R20: Diseña sistemas con arquitecturas y patrones de software.
                            </li>
                        </ul>
                    </td>
                </tr>


                <tr class="objective-row" data-id="3">
                    <td>OP3) Diseñar arquitecturas telemáticas que integren eficientemente los aspectos técnicos, económicos y sociales, garantizando la escalabilidad y la seguridad de las soluciones implementadas en entornos complejos.</td>
                </tr>
                <tr class="results-row" id="results-3">
                    <td colspan="1">
                        <ul>
                            <li>R3: Diseña sistemas distribuidos con arquitecturas telemáticas, considerando criterios de eficiencia, escalabilidad y seguridad</li>
                            <li>R5: Diseña infraestructura telemática eficiente considerando los aspectos técnicos, económicos y sociales del entorno</li>
                            <li>R20: Diseña sistemas con arquitecturas y patrones de software.
                            </li>
                            <li>R6: Analiza problemas complejos de ingeniería telemática para proponer soluciones efectivas
                            </li>

                        </ul>
                    </td>
                </tr>




                <!-- Agregar más filas para los otros objetivos y resultados -->
                <tr class="objective-row" data-id="4">
                    <td>OP4) Analizar y experimentar con datos y procesos telemáticos para generar soluciones innovadoras que respondan a las necesidades del entorno y optimicen la toma de decisiones en el ámbito de la ingeniería.</td>
                </tr>
                <tr class="results-row" id="results-4">
                    <td colspan="1">
                        <ul>
                            <li>RA4: Analiza datos a gran escala utilizando experimentación adecuada para obtener conclusiones informadas en ingeniería telemática
                            </li>
                            <li>RA10: Automatiza procesos para mejorar la eficiencia en entornos telemáticos
                            </li>
                            <li>RA12: Evalúa problemas de complejidad algorítmica para optimizar soluciones en ingeniería telemática.
                            </li>
                            <li>RA13: Aplica técnicas matemáticas para analizar datos en el contexto de soluciones telemáticas
                            </li>
                        </ul>
                    </td>
                </tr>



                <tr class="objective-row" data-id="5">
                    <td>OP5) Desarrollar la conciencia ética y la responsabilidad social en los estudiantes, promoviendo el compromiso con el bienestar de la comunidad y fomentando un crecimiento personal que integre valores fundamentales en su práctica profesional en diversos contextos culturales y disciplinarios.</td>
                </tr>
                <tr class="results-row" id="results-5">
                    <td colspan="1">
                        <ul>
                            <li>RA14: Trabaja en equipo en entornos colaborativos con habilidades interpersonales, de una manera ética.
                            </li>
                            <li>RA16: Ejecuta sus actuaciones con responsabilidad ética y profesional en el ámbito de la ingeniería telemática.
                            </li>
                            <li>RA17: Lidera equipos de gestión de proyectos en el contexto de la ingeniería telemática, promoviendo la buena comunicación y la claridad en los objetivos.
                            </li>
                            <li>RA19: Analiza el contexto social considerando elementos de responsabilidad social en la práctica de la ingeniería telemática.
                            </li>
                        </ul>
                    </td>
                </tr>



                <tr class="objective-row" data-id="6">
                    <td>OP6) Gestionar proyectos de manera efectiva, integrando herramientas y metodologías adecuadas que promuevan la colaboración, la innovación y la sostenibilidad, mientras se abordan las necesidades del entorno y se asegura el cumplimiento de los objetivos establecidos con un enfoque en la responsabilidad social y el desarrollo sostenible.</td>
                </tr>
                <tr class="results-row" id="results-6">
                    <td colspan="1">
                        <ul>
                            <li>RA8: Construye sistemas informáticos para satisfacer las necesidades del entorno
                            </li>
                            <li>RA15: Aplica estrategias de autoaprendizaje continuo para la adquisición y actualización de conocimientos en el ámbito telemático.
                            </li>
                            <li>RA17: Lidera equipos de gestión de proyectos en el contexto de la ingeniería telemática, promoviendo la buena comunicación y la claridad en los objetivos.
                            </li>
                            <li>RA18: Propone proyectos de ingeniería sustentando su viabilidad económica y técnica en el contexto de la ingeniería telemática.
                            </li>
                        </ul>
                    </td>
                </tr>



                <tr class="objective-row" data-id="7">
                    <td>OP7) Promover la responsabilidad social en todos los ámbitos de la práctica profesional, fomentando un enfoque ético y sostenible que integre las necesidades de la comunidad y el medio ambiente, con el fin de contribuir al desarrollo equitativo y a la mejora de la calidad de vida de las personas.</td>
                </tr>
                <tr class="results-row" id="results-7">
                    <td colspan="1">
                        <ul>
                            <li>RA16: Ejecuta sus actuaciones con responsabilidad ética y profesional en el ámbito de la ingeniería telemática.
                            </li>
                            <li>RA18: Propone proyectos de ingeniería sustentando su viabilidad económica y técnica en el contexto de la ingeniería telemática.
                            </li>
                            <li>RA19: Analiza el contexto social considerando elementos de responsabilidad social en la práctica de la ingeniería telemática.
                            </li>
                        </ul>
                    </td>
                </tr>





            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <script src="/proyectoGrado/public/assets/js/script_menu.js"></script>
</body>

</html>