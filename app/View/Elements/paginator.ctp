    <div class="paginator">
    <?= $this->Paginator->numbers(
        array(
            'first' => 'Primera',
            'last' => 'Última',
            'modulus' => '4',
            'separator' => '',
            'currentTag' => 'a'
        )
    ); ?>
        <div class="clearboth"></div>
    </div>
