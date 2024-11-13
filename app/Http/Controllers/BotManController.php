<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('Tôi muốn tìm sản phẩm|Muốn tìm sản phẩm|Tìm sản phẩm|Sản phẩm', function ($botman) {
            $botman->startConversation(new SanPhamConversation());
        });
        $botman->hears('Tôi muốn được tư vấn trực tiếp|Muốn được tư vấn trực tiếp|Muốn tư vấn trực tiếp|Tư vấn trực tiếp|Tư vấn|Muốn tư vấn', function ($botman) {
            $botman->startConversation(new TuVanConversation());
        });
        $botman->hears('Tôi muốn được hỗ trợ chăm sóc khách hàng|Muốn được hỗ trợ chăm sóc khách hàng|Muốn hỗ trợ chăm sóc khách hàng|Hỗ trợ chăm sóc khách hàng|hỗ trợ|Chăm sóc khách hàng ', function ($botman) {
            $botman->startConversation(new HoTroConversation());
        });


        $botman->listen();
    }
}
/**
 * Ask the user for their name when they say 'hi'.
 */
class TuVanConversation extends Conversation
{
    protected $name;
    protected $phone;
    public function TuVan()
    {
        // $this->say('Để được hỗ trợ tư vấn bạn có thể chọn: ');
        // $this->ask('Để lại thông tin liên hệ hoặc trò chuyện trực tiếp qua tin nhắn với các tư vấn viên của chúng tôi', function (Answer $answer) {
        //     $this->name = $answer->getText();
        //     $this->askName();
        // });
        $this->say('Bạn hãy để lại thông tin liên hệ của mình để được tư vấn');
        $this->askName();
    }
    public function askName()
    {
        $this->ask('Họ và Tên của bạn là:', function (Answer $answer) {
            $this->name = $answer->getText();
            $this->askPhone();
        });
    }
    public function askPhone()
    {
        $this->ask('Số điện thoại chúng tôi có thể liên hiện tới bạn là:', function (Answer $answer) {
            $this->phone = $answer->getText();
            $this->CamOn();
        });
    }
    public function CamOn()
    {
        $this->ask('Cảm ơn bạn đã để lại thông tin liên hê. Chúng tôi sẽ liên lạc với bạn nhanh nhất có thể! ', function (Answer $answer) {
            $this->phone = $answer->getText();
        });
    }
    public function run()
    {
        $this->Tuvan();
    }
}
class HoTroConversation extends Conversation
{
    protected $name;
    protected $phone;
    public function askName()
    {
        $this->ask('Họ và Tên của bạn là:', function (Answer $answer) {
            $this->name = $answer->getText();
            $this->askPhone();
        });
    }
    public function askPhone()
    {
        $this->ask('Số điện thoại chúng tôi có thể liên hiện tới bạn là:', function (Answer $answer) {
            $this->phone = $answer->getText();
            $this->CamOn();
        });
    }
    public function CamOn()
    {
        $this->ask('Cảm ơn bạn đã để lại thông tin liên hê. Chúng tôi sẽ liên lạc với bạn nhanh nhất có thể! ', function (Answer $answer) {
            $this->phone = $answer->getText();
        });
    }
    public function run()
    {
        $this->askName();
    }
}
class SanPhamConversation extends Conversation
{
    protected $name;
    protected $product;
    protected $phone;
    public function askProduct()
    {
        $this->say('Bạn đã tìm được sản phẩm bạn mong muốn chưa?');
        sleep(1);
        $this->ask('Nếu đã có rồi thì mong bạn cung cấp tên sản phẩm của bạn', function (Answer $response) {
            $product = $response->getText();
            if (in_array(strtolower($product), ['chưa', 'Chưa', 'Chưa có','chưa có'])) {
                $this->askCategory();
            } else {
                // $url ='https://pokeapi.co/api/v2/pokemon/'.urlencode($product);
                // $api = json_decode(file_get_contents($url));
                $this->say('Cảm ơn bạn đã chọn sản phẩm: ' . $product);
                $this->askName();
            }

        });
    }

    public function askName()
    {
        $this->ask('Họ và Tên của bạn là:', function (Answer $answer) {
            $this->name = $answer->getText();
            $this->askPhone();
        });
    }
    public function askPhone()
    {
        $this->ask('Số điện thoại chúng tôi có thể liên hiện tới bạn là:', function (Answer $answer) {
            $this->phone = $answer->getText();
            $this->CamOn();
        });
    }
    public function askCategory()
    {
        $this->say('Dưới đây là danh mục sản phẩm của chúng tôi:');
        sleep(1);
        $this->say('1. Đèn đường phố');
        sleep(1);
        $this->say('2. Cột đền trang trí');
        sleep(1);
        $this->say('3. Đèn pha');
        $this->ask('không biết loại sản phẩm nào bên trên phù hợp với như cầu của bạn?', function (Answer $response) {
            $ProductCate = $response->getText();
            $this->say('Dưới đây là danh sách sản phẩm của danh mục '.$ProductCate.':');
            sleep(1);
            $this->say('1. Đèn chiếu sáng VEGA');
            sleep(1);
            $this->say('2. Đèn chiếu sáng INDU');
            sleep(1);
            $this->say('3. Đèn chiếu sáng CARA');
            $this->ask('Bạn đẫ chọn được sản phẩm nào chưa? Nếu đã có rồi bạn hãy cho chúng tôi biết tên sản phẩm đó. Nếu chưa bạn luôn có thể quay lại để chọn danh mục sản phẩm khác phù hợp hơn!', function (Answer $response) {
                $product = $response->getText();
                if (in_array(strtolower($product), ['chưa', 'Chưa', 'Chưa có','chưa có'])) {
                    $this->say('Chúng tôi xin lỗi về sự bất tiện này. Như nếu muốn bạn được tư vấn bạn hãy để lại thông tin liên lạc của mình ' .$product);
                    $this->askName();
                } else if(in_array(strtolower($product), ['quay lại', 'Quay lại', 'Quay về','quay về'])) {
                    $this->askCategory();
                }
                else {
                    // $url ='https://pokeapi.co/api/v2/pokemon/'.urlencode($product);
                    // $api = json_decode(file_get_contents($url));
                    $this->say('Cảm ơn bạn đã chọn sản phẩm: ' .$product);
                    $this->askName();
                }
            });
        });


    }
    public function CamOn()
    {
        $this->ask('Cảm ơn bạn đã để lại thông tin liên hê. Chúng tôi sẽ liên lạc với bạn nhanh nhất có thể! ', function (Answer $answer) {
            $this->phone = $answer->getText();
        });
    }
    public function run()
    {
        $this->askProduct();
    }
}
