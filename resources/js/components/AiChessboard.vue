<script>
    import {chessboard} from 'vue-chessboard';

    export default {
        name: 'AiChessboard',
        extends: chessboard,
        props: [
            'fen',
            'gameid'
        ],
        methods: {
            userPlay() {
                return (orig, dest) => {
                    if (this.isPromotion(orig, dest)) {
                        this.promoteTo = this.onPromotion()
                    }
                    this.game.move({from: orig, to: dest, promotion: this.promoteTo}) // promote to queen for simplicity
                    this.board.set({
                        fen: this.game.fen()
                    })
                    this.calculatePromotions()
                    this.aiNextMove()
                };
            },
            aiNextMove(move = null) {
                let moves = this.game.moves({verbose: true})
                let randomMove = moves[Math.floor(Math.random() * moves.length)]

                if (move != null){
                    randomMove = move
                    console.log('Move is NOT random.')
                    console.log(this.toColor())
                }

                this.game.move(randomMove)

                this.board.set({
                    fen: this.game.fen(),
                    turnColor: this.toColor(),
                    movable: {
                        dests: this.possibleMoves(),
                    }
                });
            }
        },
        mounted() {
            this.board.set({
                fen: this.$props.fen,
            });

            var pusher = new Pusher('acf070fe0cc61af3c367', {
                cluster: 'eu'
            });

            var channel = pusher.subscribe('gamemoves');
            channel.bind('game-move-' + this.gameid, (data) => {
                this.aiNextMove(data);
            });
        }
    }
</script>
