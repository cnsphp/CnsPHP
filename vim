.vim 安装配置
    Bundle / astyle / clang-format / comments / cscope / JsBeautify / mru / omnicomplete / Syntastic / tagbar 
Taglist / winmanager / vim-airline / xdebug.php / ZenCoding.vim / matchit.zip / UltiSnips / YouComplateMe / eclim

////////////////////////////////////////////////////////

1)
    sudo apt-get install git vim-gnome-py2 ctags g++ cmake build-essential python-dev clang  libclang1-3.8  libclang-3.8-dev mono-complete  cscope npm  nodejs  libxml2-utils

    sudo ln -s /usr/bin/nodejs  /usr/bin/node

    git clone https://github.com/VundleVim/Vundle.vim.git  ~/.vim/bundle/Vundle.vim

////////////////////////////////////////////////////////

2) vim ~/.vimrc
"兼容模式
set nocompatible

"默认为关闭
syntax on
set hlsearch

"缩进模式
set autoindent
set cindent
set smartindent
let g:html_indent_inctags = "html,body,head,tbody"

set wildignore+=*.so,*.o,*.rar,*.out,*.deb,*.exe,*.mp3,*.jpeg,*.bmp,*.gif,*.png,*.a,*.obj,*.swp,*.zip,*.pyc,*.pyo,*.class

"文件编码
set enc=utf-8
set fenc=&encoding
set termencoding=&encoding
set fencs=utf-8,ansii,ucs-bom,gb18030,cp936,big5

if has("gui_running")
    set helplang=cn
    set langmenu=zh_CN.utf-8

    language messages zh_CN.UTF-8

    source $VIMRUNTIME/delmenu.vim
    source $VIMRUNTIME/menu.vim
endif

"使用unix文件格式
set ffs=unix,dos,mac

"打开文件光标定位到上次编辑的地方
au BufReadPost * if line("'\"") > 1 && line("'\"") <= line("$") | exe "normal! g'\"" | endif

"保存文件时删除多余空格
fun! <SID>StripTrailingWhitespaces()
    let l = line(".")
    let c = col(".")
    %s/\s\+$//e
    call cursor(l, c)
endfun
autocmd FileType vimrc,vim,sh,c,cpp,java,go,php,javascript,puppet,python,rust,twig,xml,yml,perl autocmd BufWritePre <buffer> :call <SID>StripTrailingWhitespaces()

"备份
if !isdirectory(expand('$HOME/.bak'))
    call mkdir(expand('$HOME/.bak'),'p')
endif
set backup
set backupext=.bak
set backupdir=~/.bak


""光标
"---------------------------------------
"显示匹配
set showmatch
set smarttab et

"光标在到达窗口顶部与尾时保持前后3行
set scrolloff=3


"窗口
"---------------------------------------

"在被分割的窗口间显示空白，便于阅读
set fillchars=vert:\ ,stl:\ ,stlnc:\

set wildmenu
set wildmode=list:full,longest:full,list:longest

"设置标尺
set ruler

"显示行号
"set number

""状态栏
set statusline=%<%f\ %h%m%r%=%k[%{(&fenc==\"\")?&enc:&fenc}%{(&bomb?\",BOM\":\"\")}]\ %-14.(%l,%c%V%)\ %P
set laststatus=2

"显示被修改行
set report=0


""其它
"---------------------------------------
"词典位置
set dict+=/usr/share/dict/words

"去掉操作错误时的提示声音
set novisualbell
set noerrorbells
set t_vb=
set tm=500

"设置历史
set history=2000

"ctags --c-types=f -f ~/.vim/ctags/ctag -R /usr/include/
set tags+=~/.vim/ctags/ctag

""C/C++ 按F5编译运行
map <F5> :call CompileRunGcc()<CR>
imap <F5> <Esc>:call CompileRunGcc()<CR>
func! CompileRunGcc()
    exec "w"

    " %      为文件名如 a.c
    " %<     为不加后缀的文件名如 a
    " %:p:h  path of file
    " %:p:r

    cd %:p:h

    if &filetype == 'c'
        exec "! gcc % -o %:p:r.out && %:p:r.out"

    elseif &filetype == 'cpp'
        exec "! g++ %:p -o %:p:r.out && %:p:r.out"

    elseif &filetype == 'java'
        exec "! javac % && java %<"

    elseif &filetype == 'sh'
        exec "! bash % "

    elseif &filetype == 'php'
        exec "! /usr/bin/php -f %:p"

    elseif &filetype == 'python'
        exec "! chmod +x %:p && python %:p "
    else
        echo &filetype
        echo "none file type"
    endif
endfunc


""C/C++的调试
map <F8> :call Rungdb()<CR>
func! Rungdb()
    exec "w"
    exec "!g++ % -g -o %<"
    exec "!gdb ./%<"
endfunc

"make 运行
":set makeprg=g++\ -Wall\ \ %:p

""代码折叠
set foldenable
" 折叠方法
" manual    手工折叠
" indent    使用缩进表示折叠
" expr      使用表达式定义折叠
" syntax    使用语法定义折叠
" diff      对没有更改的文本进行折叠
" marker    使用标记进行折叠, 默认标记是 {{{ 和 }}}
set foldmethod=syntax
set foldlevel=99
" 代码折叠自定义快捷键 <leader>zz
let g:FoldMethod = 0
map <leader>zz :call ToggleFold()<cr>
fun! ToggleFold()
    if g:FoldMethod == 0
        exe "normal! zM"
        let g:FoldMethod = 1
    else
        exe "normal! zR"
        let g:FoldMethod = 0
    endif
endfun

"html indent
let g:html_indent_inctags = "html,body,head,tbody"
let g:html_indent_script1 = "inc"
let g:html_indent_style1 = "inc"

"gui
if has("gui_running")

    "配色
    colo default

    "图形界面下右键显示功能菜单，默认不显示
    set mousemodel=popup

    an 10.310 &File.&Open\.\.\.<Tab>:tabnew      :browse tabnew<CR>
    if has("toolbar")
        an 1.130 ToolBar.-sep130-		<Nop>
        an icon=/usr/share/icons/gnome/16x16/actions/gtk-justify-fill.png 1.130 ToolBar.BufList :buffers<CR>
        an icon=/usr/share/icons/gnome/16x16/actions/go-first.png 1.130 ToolBar.BufFirst :bfirst<CR>
        an icon=/usr/share/icons/gnome/16x16/actions/go-next.png 1.130 ToolBar.BufPrev :bprevious<CR>
        an icon=/usr/share/icons/gnome/16x16/actions/go-next.png 1.130 ToolBar.BufNext :bnext<CR>
        an icon=/usr/share/icons/gnome/16x16/actions/go-last.png 1.130 ToolBar.BufLast :blast<CR>
        an icon=/usr/share/icons/gnome/16x16/actions/gtk-delete.png 1.130 ToolBar.BufClose   :Bd<CR>

        tmenu ToolBar.BufList  Buffer List Buffer
        tmenu ToolBar.BufFirst First Buffer
        tmenu ToolBar.BufPrev  Previous Buffer
        tmenu ToolBar.BufNext  Next Buffer
        tmenu ToolBar.BufLast  Last Buffer
        tmenu ToolBar.BufClose BufClose
    endif
endif

""""""""""""""""" Vundle """"""""""""""""""""""""
filetype off

set rtp+=~/.vim/bundle/Vundle.vim
call vundle#begin()

Plugin 'VundleVim/Vundle.vim'

"补全神器
Plugin 'Valloric/YouCompleteMe'

"语法检查
Plugin 'mbbill/echofunc'
Plugin 'scrooloose/syntastic'

"函数及关键字片段
Plugin 'SirVer/ultisnips'
Plugin 'honza/vim-snippets'

"支持html标签按%跳转
if expand('%:e') == "php" || expand('%:e') == "html"
    Bundle 'ZenCoding.vim'
    Bundle 'matchit.zip'
    Bundle 'othree/html5.vim'
endif

""PHP关键字上按K显示Manaul, 与Ycm冲突
"if expand('%:e') == "php"
"Bundle 'spf13/PIV'
"endif

if has("gui_running")
    "gvim下打开过的文件历史
    Plugin 'mru.vim'
endif

"IDE窗口集成
Bundle 'winmanager'
Bundle 'fholgado/minibufexpl.vim'
Plugin 'majutsushi/tagbar'
Plugin 'moll/vim-bbye'

"状态栏
Bundle 'bling/vim-airline'

"js语法
if expand('%:e') == "js"
    Bundle 'JavaScript-syntax'
    Bundle 'jQuery'
endif

call vundle#end()

"filetype 文件类型探测
"plugin   使用文件类型相关的插件
"indent   使用缩进
filetype plugin indent on

""""""""""""""""""" ~Vundle """""""""""""""


"""""""""""""""""" YouCompleteMe """""""""""""""
"默认配置文件路径"
let g:ycm_global_ycm_extra_conf = '~/.ycm_extra_conf.py'

"打开vim时不再询问是否加载ycm_extra_conf.py配置"
let g:ycm_confirm_extra_conf=0
set completeopt=longest,menu

"python解释器路径"
let g:ycm_path_to_python_interpreter='/usr/bin/python'

"是否开启语义补全"
let g:ycm_seed_identifiers_with_syntax=1

"是否在注释中也开启补全"
let g:ycm_complete_in_comments=1
let g:ycm_collect_identifiers_from_comments_and_strings = 1

"开始补全的字符数"
let g:ycm_min_num_of_chars_for_completion=1

"补全后自动关机预览窗口"
let g:ycm_autoclose_preview_window_after_completion=1
let g:ycm_autoclose_preview_window_after_insertion = 1

" 禁止缓存匹配项,每次都重新生成匹配项"
let g:ycm_cache_omnifunc=0

"字符串中也开启补全"
let g:ycm_complete_in_strings = 1

"设置为0则会使用syntastic, 设置为1则不会使用syntastic进行语法检查
let g:ycm_show_diagnostics_ui = 0


"离开插入模式后自动关闭预览窗口"
autocmd InsertLeave * if pumvisible() == 0|pclose|endif

"回车即选中当前项"
inoremap <expr> <CR>       pumvisible() ? '<C-y>' : '\<CR>'

"上下左右键行为"
inoremap <expr> <Down>     pumvisible() ? '\<C-n>' : '\<Down>'
inoremap <expr> <Up>       pumvisible() ? '\<C-p>' : '\<Up>'
inoremap <expr> <PageDown> pumvisible() ? '\<PageDown>\<C-p>\<C-n>' : '\<PageDown>'
inoremap <expr> <PageUp>   pumvisible() ? '\<PageUp>\<C-p>\<C-n>' : '\<PageUp>'

"""""""""""""""""" ~YouCompleteMe """""""""""""""

"""""""""""""" syntastic """"""""""""""""""""""""""
let g:statline_syntastic = 1
set statusline+=%{EchoFuncGetStatusLine()}
set statusline+=%#warningmsg#
set statusline+=%{exists('g:loaded_syntastic_plugin')?SyntasticStatuslineFlag():''}
set statusline+=%{SyntasticStatuslineFlag()}
set statusline+=%*

let g:syntastic_always_populate_loc_list = 1
let g:syntastic_auto_loc_list = 1
let g:syntastic_check_on_open = 1
let g:syntastic_check_on_wq = 1
let g:syntastic_loc_list_height = 4

let g:syntastic_css_checkers = ['csslint']
let g:syntastic_html_checkers = ['htmllint']
let g:syntastic_xml_checkers = ['xmllint']
let g:syntastic_javascript_checkers = ['jshint']
let g:syntastic_php_checkers = ['php','phpcs','phpmd']
let jshint2_command = '/usr/local/bin/jshint'
let jshint2_read = 1
let jshint2_save = 1
let jshint2_close = 0
let jshint2_confirm = 0
let jshint2_color = 0
let jshint2_error = 0
let jshint2_min_height = 3
let jshint2_max_height = 12

"""""""""""""" ~synstack """"""""""""""""""""""""""

"""""""""""""" csharp check .sln file """""""""""""
function CreateSLN()
    if ! filereadable("tmp.sln")
        silent! execute "!echo > tmp.sln"
    endif
endfunction

function DeleteSLN()
    if filereadable("tmp.sln")
        silent! execute "!rm -f tmp.sln"
    else
    endif

endfunction

if has("autocmd")
    autocmd BufEnter *.cs  call CreateSLN()
    autocmd BufWinLeave *.cs  call DeleteSLN()
endif
"""""""""""""" csharp check .sln file """""""""""""

"""""""""""""""" cscope """"""""""""""""""""""""""""""
set csprg=/usr/bin/cscope
set cscopequickfix=s-,c-,d-,i-,t-,e-
set cst

:function CreateCscope()
if(executable("/usr/bin/cscope") && has("cscope") )
    if(has('win32'))
        silent! execute "!dir /b *.c,*.cpp,*.h,*.java,*.cs,*.php > cscope.files"
    else
        let ext = expand("%:e")
        if ( ext == "c" || ext == "h")
            silent! execute "!find .  -not -regex ".*\/\..*" -a -regex ".*\(\.h\|\.c\)$"  > cscope.files"
        elseif ( ext == "java" )
            silent! execute "!find .  -not -regex ".*\/\..*" -a -regex ".*\.java$" > cscope.files"
        elseif ( ext == "cs" )
            silent! execute "!find .  -not -regex ".*\/\..*" -a -regex ".*\.cs$"  > cscope.files"
        elseif ( ext == "php" )
            silent! execute "!find . -not -regex ".*\/\..*" -a -regex ".*\.php$$" > cscope.files"
        elseif ( ext == "py" )
            silent! execute "!find . -not -regex ".*\/\..*" -a -regex ".*\.py$" > cscope.files"
        elseif ( ext == "cpp" )
            silent! execute "!find .  -not -regex ".*\/\..*" -a -regex ".*\.cpp$"  > cscope.files"
        endif
    endif
    silent! execute "!cscope -Rbkq"
    if filereadable("cscope.out")
        execute "cs add cscope.out"
    endif
endif
:endfunction


au BufNewFile,BufRead *.c,*.h,*cs,*.php,*.java,*.cpp,*.py :call CreateCscope()
"""""""""""""" ~cscope """""""""""""""""""""""""""""


"""""""""""""""" WinManager """""""""""""""""""""
"快捷键
map <F3>       :WMToggle<CR>
imap <F3> <Esc>:WMToggle<CR>

map <F4>       :Tagbar<CR>
imap <F4> <Esc>:Tagbar<CR>

if has("gui_running")
    "工具栏
    an 1.120 ToolBar.-sep120-		<Nop>
    an icon=/usr/share/icons/gnome/16x16/actions/redhat-home.png 1.120 ToolBar.WMToggle :WMToggle<CR>
    an icon=/usr/share/icons/gnome/16x16/actions/gnome-stock-text-unindent.png 1.120 ToolBar.TagbarToggle :TagbarToggle<CR>

    tmenu ToolBar.WMToggle      WMToggle
    tmenu ToolBar.TagbarToggle  TagbarToggle
endif

"winmanager
let g:winManagerWindowLayout='FileExplorer'
let g:winManagerWidth=25
let g:AutoOpenWinManager=0
let g:persistentBehaviour=0
"不显示隐藏文件
let g:explShowHiddenFiles = 0


"关闭Buffer命令
nnoremap bd :Bd<CR>

autocmd GUIEnter *  :WMToggle
let g:tagbar_width=20

"""""""""""""""" ~WinManager """""""""""""""""""""

"""""""""""""""" minibuffer """"""""""""""""""""""
let g:miniBufExplMapWindowNavVim = 1
let g:miniBufExplMapWindowNavArrows = 1
let g:miniBufExplMapCTabSwitchBufs = 1
let g:miniBufExplModSelTarget = 1
"""""""""""""""" ~minibuffer """""""""""""""""""""

"""""""""""""""" tagbar """"""""""""""""""""""""""
" Tagbar
let g:tagbar_left=0
let g:tagbar_width=20
let g:tagbar_autofocus = 1
let g:tagbar_sort = 0
let g:tagbar_compact = 1
" tag for coffee
if executable('coffeetags')
    let g:tagbar_type_coffee = {
                \ 'ctagsbin' : 'coffeetags',
                \ 'ctagsargs' : '',
                \ 'kinds' : [
                \             'f:functions',
                \             'o:object',
                \ ],
                \ 'sro' : ".",
                \ 'kind2scope' : {
                \                  'f' : 'object',
                \                  'o' : 'object',
                \ }
                \ }

    let g:tagbar_type_markdown = {
                \ 'ctagstype' : 'markdown',
                \ 'sort' : 0,
                \ 'kinds' : [
                \ 'h:sections'
                \ ]
                \ }
endif

"tagbar
"如果是c语言的程序的话，tagbar自动开启
autocmd BufReadPost  *.php,*.js,*.java,*.cs,*.cpp,*.c,*.h,*.cc,*.py,*.sh  call tagbar#autoopen()

"""""""""""""" ~tagbar """""""""""""""""


""""""""""""""" UltiSnips """""""""""""""""""""""
let g:UltiSnipsExpandTrigger="<tab>"
let g:UltiSnipsEditSplit="vertical"
"let g:UltiSnipsListSnippets="<c-e>"

"定义存放代码片段的文件夹，使用自定义和默认的，将会的到全局，有冲突的会提示
let g:UltiSnipsSnippetDirectories=["bundle/vim-snippets/UltiSnips"]
"""""""""""""""" ~UltiSnips """"""""""""""""""""""


""""""""""""""""""" ~插件 """"""""""""""""""
""""""""""""""""""" ~插件 """"""""""""""""""


"允许在有未保存的修改时切换缓冲区
set hidden

set clipboard+=unnamed      " 共享外部剪贴板
set autochdir               " 设定文件浏览器目录为当前目录

"set termencoding=utf-8 "编码转换


""""""""""""""""Eclim"""""""""""""""""""
let g:EclimCompletionMethod = 'omnifunc'

autocmd BufReadPost,FileReadPost,BufEnter   *.java :call StartEclim()

function StartEclim()
    if expand("%:e") == "java"
        silent! execute "! ps -ef | grep -v grep  | grep eclim >/dev/null 2>&1 || nohup /data/Softs/Using/eclipse_mars/eclimd& >/dev/null 2>&1 "
    endif
endfunction

""""""""""""""""~Eclim"""""""""""""""""""

"""""""""""""""" 快捷键 """"""""""""""""""
"Tab
set sw=4 expandtab ts=4

"共享剪贴板
set clipboard+=unnamed

if has("gui_running")
    "竖块选择使用C-q 且使用 h,j,k,l方向键
    source $VIMRUNTIME/mswin.vim
    behave mswin
endif

set showtabline=3
set cmdheight=1
set listchars=tab:\|\ ,

"imap <c-f>  <Esc><c-f>i
"
"imap <c-b>  <Esc><c-b>i
"
"vmap <c-d>  <CR>
"imap <c-d>  <CR>
"map <c-d>   i<CR><Esc>
"
imap <c-t>  <Esc>:bn<CR>i
map  <c-t>  :bn<CR>
"imap <c-tab>  <Esc>:bn<CR>i
"map  <c-tab>  :bn<CR>
"
"imap <c-e>  <Esc><Esc>:join<CR>i
"map <c-e>  :join<CR>
"
"imap <c-h>   <left>
"imap <c-j>   <down>
"imap <c-k>   <up>
"imap <c-l>   <right>
"
"map <c-h>   <left>
"map <c-j>   <down>
"map <c-k>   <up>
"map <c-l>   <right>

"#到行首
"map # ^

map <space>  <Esc>i <Esc>l
""""""""""""""" ~快捷键 """"""""""""""""""

"""""""""""""""" Beautify """"""""""""""""
function Beautify()
    let ext = expand("%:e")
    let f = expand("%")

    if ext == "java"
    elseif ext == "html"

        silent! execute "! html-beautify % > %.tmp; cp % %.bak; cp -f %.tmp % 2>/dev/null"

        "reload file
        :edit!
    elseif ext == "js"
        silent! execute "! js-beautify % > %.tmp; cp % %.bak; cp -f %.tmp % 2>/dev/null"

        "reload file
        :edit!
    elseif ext == "css"
        silent! execute "! css-beautify % > %.tmp; cp % %.bak; cp -f %.tmp % 2>/dev/null"

        "reload file
        :edit!
    elseif ext == "c" || ext == "cpp"
        silent! execute "! clang-format % > %.tmp; cp % %.bak; cp -f %.tmp % 2>/dev/null"

        "reload file
        :edit!
    endif
endfunction
""""""""""""""" ~Beautify """"""""""""""""

function GetFuncDefine()

endfunc

"colorscheme editplus
colorscheme codeblocks-dark

let g:EchoFuncShowOnStatus = 1
let g:EchoFuncLangsUsed = ["java","cpp", "c"]

"退格键
set backspace=indent,eol,start
set whichwrap=b,s,<,>,[,]

////////////////////////////////////////////////////////

3) vim 

    :PluginInstall

////////////////////////////////////////////////////////

4)
 .安装completer
     cd  ~/.vim/bundle/YouCompleteMe/ 
     ./install.py --clang-completer  --tern-completer  --omnisharp-completer

  .编译第三方工具 （此步骤一定要在上一步./install.py后面，否则不会自动补全） 
    cd  ~/.vim/bundle/YouCompleteMe/third_party/ycmd
    ./build.py  --clang-completer    --tern-completer  --omnisharp-completer

    sudo npm install -g csslint htmllint-cli jshint 

    sudo vim /usr/local/bin/csslint        
修改
        if (ignore) {
            ruleset = CSSLint.getRuleset();
            ignore.split(",").forEach(function(value){
                ruleset[value] = 0;
            });
        }
为        
        ruleset = CSSLint.getRuleset();
        ruleset["ids"]=0;
        if (ignore) {
            ignore.split(",").forEach(function(value){
                ruleset[value] = 0;
            });
        }


    vim ~/.vim/bundle/YouCompleteMe/third_party/ycmd/cpp/ycm/.ycm_extra_conf.py 

     '-isystem',
     '/usr/include',
