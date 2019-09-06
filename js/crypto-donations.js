jQuery(document).ready(function(){
        jQuery('[data-toggle="popover"]').popover({
                trigger: 'focus'
        });   
});

function processBitcoinWidget(address) {
        jQuery.getJSON('https://blockstream.info/api/address/' + address, function (data) {
                $bitcoin_amount = data['chain_stats']['funded_txo_sum'];
                jQuery('#crypto_donations_info_bitcoin_bubble').text(($bitcoin_amount / 100).toLocaleString() + ' ƀ');
                jQuery('#crypto_donations_info_bitcoin_amount').text(($bitcoin_amount / 100).toLocaleString() + ' ƀ');
                jQuery('#crypto_donations_info_bitcoin_explorer').attr('href', 'https://blockstream.info/address/' + address);
                jQuery('#crypto_donations_info_bitcoin_qr').empty();
                jQuery('#crypto_donations_info_bitcoin_qr').qrcode({render: 'div', ecLevel: 'M', size: 240, text: address});
        });
}

function processEthereumWidget(address, apiKey) {
        jQuery.getJSON('https://api.etherscan.io/api?module=account&action=balance&address=' + address + '&tag=latest&apikey=' + apiKey, function (data) {
                $ethereum_amount = data['result'];
                jQuery('#crypto_donations_info_ethereum_bubble').text(($ethereum_amount / 100000000000000000).toLocaleString());
                jQuery('#crypto_donations_info_ethereum_amount').text(($ethereum_amount / 100000000000000000).toLocaleString() + ' ETH');
                jQuery('#crypto_donations_info_ethereum_explorer').attr('href', 'https://etherscan.io/address/' + address);
                jQuery('#crypto_donations_info_ethereum_qr').empty()
                jQuery('#crypto_donations_info_ethereum_qr').qrcode({render: 'div', ecLevel: 'M', size: 240, text: address});
        });
}

function processLitecoinWidget(address) {
        jQuery.getJSON('https://api.blockchair.com/litecoin/dashboards/address/' + address, function (data) {
                $litecoin_amount = data['data'][address]['address']['received'];
                jQuery('#crypto_donations_info_litecoin_bubble').text(($litecoin_amount / 1000000000).toLocaleString());
                jQuery('#crypto_donations_info_litecoin_amount').text(($litecoin_amount / 1000000000).toLocaleString() + ' LTC');
                jQuery('#crypto_donations_info_litecoin_explorer').attr('href', 'https://blockchair.com/litecoin/address/' + address);
                jQuery('#crypto_donations_info_litecoin_qr').empty()
                jQuery('#crypto_donations_info_litecoin_qr').qrcode({render: 'div', ecLevel: 'M', size: 240, text: address});
        });
}