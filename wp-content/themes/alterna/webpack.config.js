// node's native package 'path'
const path = require('path');
const Fiber = require('fibers');
const webpack = require('webpack'); // reference to webpack Object
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const glob = require('glob');

const env = process.env.NODE_ENV || 'development';
const isDevEnv = env == 'development'

// Constant with our paths
const paths = {
    DIST: path.resolve(__dirname, 'dist'),
    MAIN: path.resolve(__dirname, 'js'),
    ROOT: path.resolve(__dirname)
};

// Webpack configuration
module.exports = {
    entry: {
        mainjs: glob.sync("./js/**/*(custom.js|jquery.theme.js)"),
        maincss: glob.sync("./**/*(style.css|alterna.css)"),
    },
    output: {
        path: paths.DIST,
        filename: '[name].min.js'
    },
    watch: true,

	externals: {
	  jquery: 'jQuery'
    },
    
    plugins: [
        new webpack.ProvidePlugin({
          $: 'jquery',
          jQuery: 'jquery',
          Popper: 'popper.js'
        }),
        new UglifyJSPlugin(),
        new MiniCssExtractPlugin({
            filename: '[name].min.css',
            chunkFilename: '[id].min.css',
        }),
    ],

    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: ['babel-loader']
            },{
                test: /\.(woff|woff2|eot|ttf|otf|png|svg|jpg|gif)$/,
                use: {
                  loader: 'url-loader',
                  options: {
                    limit: 1000, //bytes
                    name: '[hash:7].[ext]',
                    outputPath: 'assets'
                  }
                }
            },{
            	test: /\.css$/,
                use: [
                    isDevEnv ? {loader: "style-loader"} : { loader: MiniCssExtractPlugin.loader },
                    {
                        loader: "css-loader"
                    },{
                        loader: 'postcss-loader',
                        options: {
                            ident: 'postcss',
                            plugins: [
                                require('autoprefixer')({
                				    'browsers': ['> 1%', 'last 2 versions']
                				}),
                                require('cssnano')({ preset: 'default' })
                            ],
                            minimize: true
                        }
                    },{
                        loader: "sass-loader",
                        options: {
                            implementation: require("sass"),
                            fiber: Fiber
                        }
                    }
                ],
            },

        ],
    },
    resolve: {
        extensions: ['.js', '.jsx'],
    },
};
