<?php use Illuminate\Support\Arr; ?>
<h1>Events: <?php echo round($totalTime * 100, 2) ?> ms</h1>

<div class="tracy-inner Laravel-EventPanel">
    <div class="tracy-inner-container">
        <table>
            <tr>
                <th>Event</th>
                <th>Execute Time</th>
            </tr>
            <?php foreach ($events as $key => $value): ?>
                <tr>
                    <th>
                        <span class="tracy-dump-object"><?php echo Arr::get($value, 'key') ?></span><br />
                        <?php echo Arr::get($value, 'editorLink') ?><br />
                        <?php echo round(Arr::get($value, 'execTime', 0) * 100, 2) ?> ms
                    </th>
                    <td>
                        <?php
                            echo Tracy\Dumper::toHtml(Arr::get($value, 'payload'), [
                                Tracy\Dumper::LIVE => true,
                                Tracy\Dumper::TRUNCATE => 50,
                                Tracy\Dumper::COLLAPSE => true,
                            ]);
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
