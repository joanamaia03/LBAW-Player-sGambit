@extends('layouts.app')

@section('content')

<body>
    <main>
        <div id="faq" style="margin:10px;">
            <link rel="stylesheet" href="../css/style.css">
            <h1>FAQ about Player's Gambit</h1>
            <br></br>
            <section id="conteudo">
                <div class="faq-item">
                    <label for="faq1">How do I create an account?<span class="seta">&#9660;</span></label>           
                    <input type="checkbox" id="faq1">
                    <div class="resposta" id="mostra1">
                        You just have to register by clicking on the register button on the right and follow the steps
                    </div><br></br>
                </div>   
                <div class="faq-item"> 
                    <label for="faq3">Can I change my username?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq3">
                    <div class="resposta" id="mostra3">
                        Yes u can! You just have to go to your profile page and edit your profile
                    </div><br></br>
                </div> 
                <div class="faq-item">
                    <label for="faq4">What is this website?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq4">
                    <div class="resposta" id="mostra4">
                        This is as auction website where you can bid for any kind of games, since the perfect game has no fixed price. But if you want to know more, just go to our About Us section
                    </div><br></br>
                </div> 
                <div class="faq-item">
                    <label for="faq5">How can I add new auctions?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq5">
                    <div class="resposta" id="mostra5">
                        You need to go to your profile page and click on the 'Create Auction' button but if you have more doubts you can check our Guide section
                    </div><br></br>
                </div> 
                <div class="faq-item">    
                    <label for="faq6">Can I edit or delete my auctions?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq6">
                    <div class="resposta" id="mostra6">
                        You can edit your auctions but you can't delete them but if you have more doubts you can check our Guide section
                    </div><br></br>
                </div> 
                <div class="faq-item">
                    <label for="faq7">Can I customize my user profile on the website?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq7">
                    <div class="resposta" id="mostra7">
                        Yes you can! You just have to go to your profile page and edit your profile
                    </div><br></br>
                </div> 
                <div class="faq-item">
                    <label for="faq8">Are there any age restrictions for accessing certain parts of the website?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq8">
                    <div class="resposta" id="mostra8">
                        You just have to authenticate yourself and you can enjoy the website as you want
                    </div><br></br>
                </div> 
                <div class="faq-item">
                    <label for="faq9">Can I share content from the website on social media?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq9">
                    <div class="resposta" id="mostra9">
                        Of course you can! Please share our amazing website with everyone
                    </div><br></br>
                </div> 
                <div class="faq-item">
                    <label for="faq10">How frequently is the website updated with new content?<span class="seta">&#9660;</span></label>
                    <input type="checkbox" id="faq10">
                    <div class="resposta" id="mostra10">
                        The website is in constant updating because there's always new auctions and new bids created by users all around the world
                    </div><br></br>
                </div> 
            </section>    
        </div> 
    </main>
</body>    

@endsection