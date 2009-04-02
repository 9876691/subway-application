del /Q app\tmp\cache\persistent\*.*
copy app\tmp\cache\views\empty app\tmp\cache\persistent\

del /Q app\tmp\cache\models\*.*
copy app\tmp\cache\views\empty app\tmp\cache\models\


del /Q app\tmp\logs\*.log

"C:\Program Files\Java\jdk1.6.0_06\bin\jar" -cvf SubwayCRM.zip cake install vendors app/tmp app/config app/controllers app/locale app/models app/plugins app/views app/webroot index.php

pause