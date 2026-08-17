<?php
$map = [
    '📚' => '<i class="fas fa-book"></i>',
    '✅' => '<i class="fas fa-check-circle"></i>',
    '📝' => '<i class="fas fa-edit"></i>',
    '⭐' => '<i class="fas fa-star text-yellow-400"></i>',
    '🚪' => '<i class="fas fa-sign-out-alt"></i>',
    '👤' => '<i class="fas fa-user"></i>',
    '👨‍🏫' => '<i class="fas fa-chalkboard-teacher"></i>',
    '⚙️' => '<i class="fas fa-cog"></i>',
    '👨‍🎓' => '<i class="fas fa-user-graduate"></i>',
    '💻' => '<i class="fas fa-laptop-code"></i>',
    '🌐' => '<i class="fas fa-globe"></i>',
    '🗄️' => '<i class="fas fa-server"></i>',
    '🔐' => '<i class="fas fa-lock"></i>',
    '📱' => '<i class="fas fa-mobile-alt"></i>',
    '💬' => '<i class="fas fa-comments"></i>',
    '🏆' => '<i class="fas fa-trophy text-yellow-400"></i>',
    '🎓' => '<i class="fas fa-graduation-cap"></i>',
    '📍' => '<i class="fas fa-map-marker-alt"></i>',
    '🚀' => '<i class="fas fa-rocket"></i>',
    '📹' => '<i class="fas fa-video"></i>',
    '⚡' => '<i class="fas fa-bolt text-yellow-500"></i>',
    '📊' => '<i class="fas fa-chart-bar"></i>',
    '🎯' => '<i class="fas fa-bullseye"></i>',
    '🎉' => '<i class="fas fa-glass-cheers"></i>',
    '👋' => '<i class="fas fa-hand-sparkles"></i>',
    '💯' => '<i class="fas fa-certificate text-green-500"></i>',
    '⏱️' => '<i class="fas fa-stopwatch"></i>',
    '📆' => '<i class="fas fa-calendar-alt"></i>',
];

$dir = new RecursiveDirectoryIterator('/Users/laodefardin/Dosen/webpti/lms-pti/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = str_replace(array_keys($map), array_values($map), $content);
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    }
}
