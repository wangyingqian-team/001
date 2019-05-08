<?php
namespace Liviator\Whisper\Transport\Whisper;

use Lawoole\Homer\Transport\Whisper\WhisperServerSocket as BaseWhisperServerSocket;

class WhisperServerSocket extends BaseWhisperServerSocket
{
    /**
     * 配置选项
     *
     * @var array
     */
    protected $options = [
        'tcp_fastopen'          => true,
        'open_tcp_nodelay'      => true,
        'open_length_check'     => true,
        'package_max_length'    => 8388608,
        'package_length_type'   => 'N',
        'package_length_offset' => 4,
        'package_body_offset'   => 8,
    ];
}