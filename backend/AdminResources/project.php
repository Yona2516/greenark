<?php
// app/Filament/Resources/ProjectResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options([
                                'residential' => 'Residential',
                                'commercial' => 'Commercial',
                                'custom-builds' => 'Custom Builds',
                                'renovations' => 'Renovations',
                            ]),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'planning' => 'Planning',
                                'in-progress' => 'In Progress',
                                'completed' => 'Completed',
                                'on-hold' => 'On Hold',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\Textarea::make('short_description')
                            ->required()
                            ->maxLength(500)
                            ->rows(3),
                        Forms\Components\RichEditor::make('description')
                            ->required(),
                        Forms\Components\RichEditor::make('case_study'),
                    ]),

                Forms\Components\Section::make('Project Details')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('budget')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\DatePicker::make('start_date'),
                        Forms\Components\DatePicker::make('completion_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Features')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery')
                            ->multiple()
                            ->image()
                            ->directory('projects/gallery')
                            ->maxFiles(10),
                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\TextInput::make('feature')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->collapsible()
                            ->defaultItems(1),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->default(false),
                        Forms\Components\Toggle::make('is_published')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gallery')
                    ->limit(1)
                    ->height(60),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'residential',
                        'success' => 'commercial',
                        'warning' => 'custom-builds',
                        'danger' => 'renovations',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'planning',
                        'warning' => 'in-progress',
                        'success' => 'completed',
                        'danger' => 'on-hold',
                    ]),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'residential' => 'Residential',
                        'commercial' => 'Commercial',
                        'custom-builds' => 'Custom Builds',
                        'renovations' => 'Renovations',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'planning' => 'Planning',
                        'in-progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on-hold' => 'On Hold',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TernaryFilter::make('is_published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}